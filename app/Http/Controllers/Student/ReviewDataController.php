<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ReviewDataController extends Controller
{
    /**
     * Display a listing of the user's registration data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.event.profile.review-data');
    }

    /**
     * Update review data (placeholder for future implementation).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // This is a placeholder for future implementation
        return redirect()->back()->with('info', 'Edit functionality will be available soon.');
    }

    /**
     * Refresh review data (placeholder for future implementation).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        // This is a placeholder for future implementation
        return redirect()->back()->with('success', 'Data refreshed successfully.');
    }

    /**
     * View document in browser without forcing download.
     *
     * @param  string  $teamId
     * @param  string  $documentType
     * @return \Illuminate\Http\Response
     */
    public function viewDocument($teamId, $documentType)
    {
        try {
            $currentUser = Auth::user();
            $currentUserName = strtolower(trim($currentUser->name ?? ''));
            
            if (empty($currentUserName)) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Find the record and document
            $result = $this->findRecordAndDocument($teamId, $documentType, $currentUserName);
            
            if (!$result['success']) {
                return redirect()->back()->with('error', $result['message']);
            }

            $record = $result['record'];
            $filePath = $result['file_path'];
            $fileName = $result['file_name'];
            $recordType = $result['record_type'];
            $documentType = $result['document_type'];

            // Find the file
            $fullPath = $this->findDocumentFile($filePath, $fileName, $recordType, $documentType);

            if (!$fullPath || !File::exists($fullPath)) {
                Log::error('Document file not found for viewing', [
                    'path' => $filePath,
                    'full_path' => $fullPath,
                    'document_type' => $documentType
                ]);
                
                // Try to find alternative path
                $fullPath = $this->findAlternativeDocumentPath($record, $documentType, $fileName);
                
                if (!$fullPath || !File::exists($fullPath)) {
                    return redirect()->back()->with('error', 'Document file not found on server.');
                }
            }

            // Determine MIME type
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'txt' => 'text/plain',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];

            $mime = $mimeTypes[$extension] ?? 'application/octet-stream';

            // Return file as inline (view in browser)
            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Error viewing document: ' . $e->getMessage(), [
                'team_id' => $teamId,
                'document_type' => $documentType,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to view document: ' . $e->getMessage());
        }
    }

    /**
     * Download document for specific team and document type.
     *
     * @param  string  $teamId
     * @param  string  $documentType
     * @return \Illuminate\Http\Response
     */
    public function downloadDocument($teamId, $documentType)
    {
        try {
            $currentUser = Auth::user();
            $currentUserName = strtolower(trim($currentUser->name ?? ''));
            
            if (empty($currentUserName)) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Find the record and document
            $result = $this->findRecordAndDocument($teamId, $documentType, $currentUserName);
            
            if (!$result['success']) {
                return redirect()->back()->with('error', $result['message']);
            }

            $record = $result['record'];
            $filePath = $result['file_path'];
            $fileName = $result['file_name'];
            $recordType = $result['record_type'];
            $documentType = $result['document_type'];

            // Find the file
            $fullPath = $this->findDocumentFile($filePath, $fileName, $recordType, $documentType);

            if (!$fullPath || !File::exists($fullPath)) {
                Log::error('Document file not found for download', [
                    'path' => $filePath,
                    'full_path' => $fullPath,
                    'document_type' => $documentType
                ]);
                
                // Try to find alternative path
                $fullPath = $this->findAlternativeDocumentPath($record, $documentType, $fileName);
                
                if (!$fullPath || !File::exists($fullPath)) {
                    return redirect()->back()->with('error', 'Document file not found on server.');
                }
            }

            // Generate download filename
            $downloadFilename = $this->generateDownloadFilename($record, $documentType, $fileName);

            // Download the file
            return response()->download($fullPath, $downloadFilename, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $downloadFilename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading document: ' . $e->getMessage(), [
                'team_id' => $teamId,
                'document_type' => $documentType,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to download document: ' . $e->getMessage());
        }
    }

    /**
     * Find record and document information.
     *
     * @param  string  $teamId
     * @param  string  $documentType
     * @param  string  $userName
     * @return array
     */
    private function findRecordAndDocument($teamId, $documentType, $userName)
    {
        $record = null;
        $filePath = null;
        $fileName = null;
        $recordType = null;

        // Check in player_list
        $player = DB::table('player_list')
            ->where('team_id', $teamId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$userName])
            ->first();

        if ($player && !empty($player->$documentType)) {
            $record = $player;
            $recordType = 'player';
            $filePath = 'player_docs/' . basename($player->$documentType);
            $fileName = basename($player->$documentType);
        }

        // If not found in player, check dancer_list
        if (!$record) {
            $dancer = DB::table('dancer_list')
                ->where('team_id', $teamId)
                ->whereRaw('LOWER(TRIM(name)) = ?', [$userName])
                ->first();

            if ($dancer && !empty($dancer->$documentType)) {
                $record = $dancer;
                $recordType = 'dancer';
                $filePath = 'dancer_docs/' . basename($dancer->$documentType);
                $fileName = basename($dancer->$documentType);
            }
        }

        // If not found, check official_list
        if (!$record) {
            $official = DB::table('official_list')
                ->where('team_id', $teamId)
                ->whereRaw('LOWER(TRIM(name)) = ?', [$userName])
                ->first();

            if ($official && !empty($official->$documentType)) {
                $record = $official;
                $recordType = 'official';
                
                // Handle different document paths for officials
                $documentPath = $this->getOfficialDocumentPath($documentType);
                $filePath = $documentPath . '/' . basename($official->$documentType);
                $fileName = basename($official->$documentType);
            }
        }

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Document not found or you do not have permission to access it.'
            ];
        }

        if (empty($fileName)) {
            return [
                'success' => false,
                'message' => 'Document file not specified.'
            ];
        }

        return [
            'success' => true,
            'record' => $record,
            'record_type' => $recordType,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'document_type' => $documentType
        ];
    }

    /**
     * Find alternative document path.
     *
     * @param  object  $record
     * @param  string  $documentType
     * @param  string  $fileName
     * @return string|null
     */
    private function findAlternativeDocumentPath($record, $documentType, $fileName)
    {
        $alternativePaths = [];

        // Try different base paths
        if ($record->type === 'player' || $record->type === 'dancer') {
            $alternativePaths[] = public_path('uploads/' . $record->type . '_docs/' . $fileName);
            $alternativePaths[] = storage_path('app/' . $record->type . '_docs/' . $fileName);
            $alternativePaths[] = public_path('storage/' . $record->type . '_docs/' . $fileName);
        } elseif ($record->type === 'official') {
            $basePath = $this->getOfficialDocumentPath($documentType);
            $alternativePaths[] = public_path($basePath . '/' . $fileName);
            $alternativePaths[] = storage_path('app/public/' . $basePath . '/' . $fileName);
            $alternativePaths[] = public_path('storage/' . $basePath . '/' . $fileName);
        }

        // Special case for payment proof
        if ($documentType === 'payment_proof') {
            $alternativePaths[] = public_path('payment_proofs/' . $fileName);
            $alternativePaths[] = storage_path('app/public/payment_proofs/' . $fileName);
            $alternativePaths[] = public_path('storage/payment_proofs/' . $fileName);
        }

        foreach ($alternativePaths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Get document path for official based on document type.
     *
     * @param  string  $documentType
     * @return string
     */
    private function getOfficialDocumentPath($documentType)
    {
        $paths = [
            'formal_photo' => 'uploads/officials/formal_photos',
            'license_photo' => 'uploads/officials/license_photos',
            'identity_card' => 'uploads/officials/identity_cards',
        ];

        return $paths[$documentType] ?? 'uploads/officials';
    }

    /**
     * Find document file in various possible locations.
     *
     * @param  string  $filePath
     * @param  string  $fileName
     * @param  string  $recordType
     * @param  string  $documentType
     * @return string|null
     */
    private function findDocumentFile($filePath, $fileName, $recordType, $documentType)
    {
        // Possible locations to check
        $locations = [
            // Standard storage paths
            storage_path('app/public/' . $filePath),
            public_path('storage/' . $filePath),
            public_path($filePath),
            
            // Direct paths
            storage_path('app/public/' . dirname($filePath) . '/' . $fileName),
            public_path('storage/' . dirname($filePath) . '/' . $fileName),
            public_path(dirname($filePath) . '/' . $fileName),
            
            // Legacy paths
            storage_path('app/' . $filePath),
            base_path($filePath),
        ];

        // Add specific locations for player/dancer payment proof
        if ($recordType === 'player' && $documentType === 'payment_proof') {
            $locations[] = storage_path('app/public/payment_proofs/' . $fileName);
            $locations[] = public_path('storage/payment_proofs/' . $fileName);
            $locations[] = public_path('payment_proofs/' . $fileName);
            $locations[] = storage_path('app/payment_proofs/' . $fileName);
        }

        // Add specific locations for officials
        if ($recordType === 'official') {
            $basePath = $this->getOfficialDocumentPath($documentType);
            $locations[] = storage_path('app/public/' . $basePath . '/' . $fileName);
            $locations[] = public_path('storage/' . $basePath . '/' . $fileName);
            $locations[] = public_path($basePath . '/' . $fileName);
            $locations[] = storage_path('app/' . $basePath . '/' . $fileName);
        }

        // Remove duplicates
        $locations = array_unique($locations);

        foreach ($locations as $location) {
            if (File::exists($location) && !is_dir($location)) {
                return $location;
            }
        }

        return null;
    }

    /**
     * Generate a meaningful filename for download.
     *
     * @param  object  $record
     * @param  string  $documentType
     * @param  string  $originalFileName
     * @return string
     */
    private function generateDownloadFilename($record, $documentType, $originalFileName)
    {
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $cleanName = preg_replace('/[^a-z0-9]+/', '-', strtolower($record->name ?? 'document'));
        
        $documentLabels = [
            'birth_certificate' => 'Akta_Kelahiran',
            'kk' => 'Kartu_Keluarga',
            'shun' => 'SHUN',
            'report_identity' => 'Identitas_Raport',
            'last_report_card' => 'Raport_Terakhir',
            'formal_photo' => 'Foto_Formal',
            'assignment_letter' => 'Surat_Tugas',
            'payment_proof' => 'Bukti_Pembayaran',
            'license_photo' => 'Lisensi',
            'identity_card' => 'KTP_SIM',
        ];

        $label = $documentLabels[$documentType] ?? ucfirst(str_replace('_', ' ', $documentType));
        $teamId = $record->team_id ?? 'unknown';
        
        return sprintf(
            '%s_%s_%s_%s.%s',
            $cleanName,
            $label,
            $teamId,
            date('Ymd'),
            $extension
        );
    }

    /**
     * Get user statistics for dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        try {
            $currentUser = Auth::user();
            $currentUserName = strtolower(trim($currentUser->name ?? ''));

            if (empty($currentUserName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Count player records
            $playerCount = DB::table('player_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->count();

            // Count dancer records
            $dancerCount = DB::table('dancer_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->count();

            // Count official records
            $officialCount = DB::table('official_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->count();

            // Count as leader/captain
            $leaderCount = DB::table('player_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->where('role', 'Leader')
                ->count();

            $dancerLeaderCount = DB::table('dancer_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->where('role', 'Leader')
                ->count();

            $officialLeaderCount = DB::table('official_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->where('role', 'Leader')
                ->count();

            // Get all team IDs
            $teamIds = collect();
            
            $playerTeams = DB::table('player_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->pluck('team_id');
            $teamIds = $teamIds->concat($playerTeams);
            
            $dancerTeams = DB::table('dancer_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->pluck('team_id');
            $teamIds = $teamIds->concat($dancerTeams);
            
            $officialTeams = DB::table('official_list')
                ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
                ->pluck('team_id');
            $teamIds = $teamIds->concat($officialTeams);

            $totalTeams = $teamIds->unique()->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_records' => $playerCount + $dancerCount + $officialCount,
                    'total_teams' => $totalTeams,
                    'player_count' => $playerCount,
                    'dancer_count' => $dancerCount,
                    'official_count' => $officialCount,
                    'leader_count' => $leaderCount + $dancerLeaderCount + $officialLeaderCount,
                    'captain_count' => $leaderCount,
                    'dancer_leader_count' => $dancerLeaderCount,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics'
            ], 500);
        }
    }

    /**
     * Get detailed record by ID and type.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecordDetail(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'type' => 'required|in:player,dancer,official'
            ]);

            $currentUser = Auth::user();
            $currentUserName = strtolower(trim($currentUser->name ?? ''));

            if (empty($currentUserName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $record = null;

            switch ($request->type) {
                case 'player':
                    $record = DB::table('player_list')
                        ->select(
                            'player_list.*',
                            'team_list.school_name as team_school_name',
                            'team_list.competition',
                            'team_list.team_category',
                            'team_list.season',
                            'team_list.series'
                        )
                        ->leftJoin('team_list', 'player_list.team_id', '=', 'team_list.team_id')
                        ->where('player_list.id', $request->id)
                        ->whereRaw('LOWER(TRIM(player_list.name)) = ?', [$currentUserName])
                        ->first();
                    break;

                case 'dancer':
                    $record = DB::table('dancer_list')
                        ->select(
                            'dancer_list.*',
                            'team_list.school_name as team_school_name',
                            'team_list.competition',
                            'team_list.team_category',
                            'team_list.season',
                            'team_list.series'
                        )
                        ->leftJoin('team_list', 'dancer_list.team_id', '=', 'team_list.team_id')
                        ->where('dancer_list.id', $request->id)
                        ->whereRaw('LOWER(TRIM(dancer_list.name)) = ?', [$currentUserName])
                        ->first();
                    break;

                case 'official':
                    $record = DB::table('official_list')
                        ->select(
                            'official_list.*',
                            'team_list.school_name as team_school_name',
                            'team_list.competition',
                            'team_list.team_category',
                            'team_list.season',
                            'team_list.series'
                        )
                        ->leftJoin('team_list', 'official_list.team_id', '=', 'team_list.team_id')
                        ->where('official_list.id', $request->id)
                        ->whereRaw('LOWER(TRIM(official_list.name)) = ?', [$currentUserName])
                        ->first();
                    break;
            }

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found'
                ], 404);
            }

            // Format dates
            if (!empty($record->birthdate)) {
                $record->birthdate_formatted = \Carbon\Carbon::parse($record->birthdate)->format('d M Y');
            }
            
            if (!empty($record->created_at)) {
                $record->created_at_formatted = \Carbon\Carbon::parse($record->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';
            }
            
            if (!empty($record->updated_at)) {
                $record->updated_at_formatted = \Carbon\Carbon::parse($record->updated_at)->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';
            }

            // Check document existence
            $documents = [];
            $documentFields = $this->getDocumentFields($record->type ?? $request->type);
            
            foreach ($documentFields as $field) {
                if (!empty($record->$field)) {
                    $documents[$field] = [
                        'exists' => true,
                        'filename' => basename($record->$field),
                        'url_view' => route('student.review.document.view', [
                            'teamId' => $record->team_id,
                            'documentType' => $field
                        ]),
                        'url_download' => route('student.review.document.download', [
                            'teamId' => $record->team_id,
                            'documentType' => $field
                        ])
                    ];
                } else {
                    $documents[$field] = ['exists' => false];
                }
            }
            
            $record->documents = $documents;

            return response()->json([
                'success' => true,
                'data' => $record
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting record detail: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get record details'
            ], 500);
        }
    }

    /**
     * Get document fields based on record type.
     *
     * @param  string  $type
     * @return array
     */
    private function getDocumentFields($type)
    {
        $fields = [
            'player' => [
                'birth_certificate',
                'kk',
                'shun',
                'report_identity',
                'last_report_card',
                'formal_photo',
                'assignment_letter',
                'payment_proof'
            ],
            'dancer' => [
                'birth_certificate',
                'kk',
                'shun',
                'report_identity',
                'last_report_card',
                'formal_photo',
                'assignment_letter',
                'payment_proof'
            ],
            'official' => [
                'formal_photo',
                'license_photo',
                'identity_card'
            ]
        ];

        return $fields[$type] ?? [];
    }

    /**
     * Check if user has access to a specific team/record.
     *
     * @param  string  $teamId
     * @param  string|null  $recordId
     * @param  string|null  $recordType
     * @return bool
     */
    private function checkAccess($teamId, $recordId = null, $recordType = null)
    {
        $currentUser = Auth::user();
        $currentUserName = strtolower(trim($currentUser->name ?? ''));

        if (empty($currentUserName)) {
            return false;
        }

        // Check if user is part of this team
        $inPlayer = DB::table('player_list')
            ->where('team_id', $teamId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
            ->exists();

        if ($inPlayer) return true;

        $inDancer = DB::table('dancer_list')
            ->where('team_id', $teamId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
            ->exists();

        if ($inDancer) return true;

        $inOfficial = DB::table('official_list')
            ->where('team_id', $teamId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$currentUserName])
            ->exists();

        return $inOfficial;
    }

    /**
     * Verify that the authenticated user owns the requested data.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOwnership(Request $request)
    {
        try {
            $request->validate([
                'team_id' => 'required',
                'record_id' => 'nullable',
                'record_type' => 'nullable|in:player,dancer,official'
            ]);

            $hasAccess = $this->checkAccess(
                $request->team_id,
                $request->record_id,
                $request->record_type
            );

            return response()->json([
                'success' => true,
                'has_access' => $hasAccess
            ]);

        } catch (\Exception $e) {
            Log::error('Error verifying ownership: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify ownership'
            ], 500);
        }
    }

    /**
     * Get document thumbnail/preview if available.
     *
     * @param  string  $teamId
     * @param  string  $documentType
     * @return \Illuminate\Http\Response
     */
    public function getDocumentThumbnail($teamId, $documentType)
    {
        try {
            $currentUser = Auth::user();
            $currentUserName = strtolower(trim($currentUser->name ?? ''));
            
            if (empty($currentUserName)) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $result = $this->findRecordAndDocument($teamId, $documentType, $currentUserName);
            
            if (!$result['success']) {
                return response()->json(['error' => $result['message']], 404);
            }

            $fileName = $result['file_name'];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Only return thumbnail for images
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['error' => 'Thumbnail not available'], 404);
            }

            $fullPath = $this->findDocumentFile(
                $result['file_path'], 
                $fileName, 
                $result['record_type'], 
                $documentType
            );

            if (!$fullPath || !File::exists($fullPath)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ];

            $mime = $mimeTypes[$extension] ?? 'image/jpeg';

            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=86400',
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting document thumbnail: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get thumbnail'], 500);
        }
    }
}