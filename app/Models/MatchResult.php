<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    use HasFactory;

    protected $table = 'match_results';

    protected $fillable = [
        'match_date',
        'season',
        'competition',
        'competition_type',
        'series',
        'phase',
        'team1_id',
        'team2_id',
        'score_1',
        'score_2',
        'status',
        'scoresheet',
        'scoresheet_original_name',
    ];

    // Casting untuk memastikan tipe data
    protected $casts = [
        'match_date' => 'date',
        'score_1' => 'integer',
        'score_2' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Nilai default untuk model
    protected $attributes = [
        'status' => 'draft',
        'score_1' => 0,
        'score_2' => 0,
    ];

    // Scope untuk memfilter berdasarkan status
    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeBySeason($query, $season)
    {
        return $query->where('season', $season);
    }

    public function scopeByCompetition($query, $competition)
    {
        return $query->where('competition', $competition);
    }

    public function scopeByCompetitionType($query, $competitionType)
    {
        return $query->where('competition_type', $competitionType);
    }

    public function scopeBySeries($query, $series)
    {
        return $query->where('series', $series);
    }

    public function scopeByPhase($query, $phase)
    {
        return $query->where('phase', $phase);
    }

    // ========== PERBAIKAN RELATIONSHIP ==========
    
    // Relationship dengan TeamList untuk team1 dengan fallback
    public function team1()
    {
        return $this->belongsTo(TeamList::class, 'team1_id', 'team_id')
                    ->withDefault([
                        'team_id' => null,
                        'school_name' => 'Team Not Found',
                        'school_logo' => null
                    ]);
    }

    // Relationship dengan TeamList untuk team2 dengan fallback
    public function team2()
    {
        return $this->belongsTo(TeamList::class, 'team2_id', 'team_id')
                    ->withDefault([
                        'team_id' => null,
                        'school_name' => 'Team Not Found',
                        'school_logo' => null
                    ]);
    }

    // Relationship dengan AddData untuk season
    public function seasonData()
    {
        return $this->belongsTo(AddData::class, 'season', 'season_name');
    }

    // Relationship dengan AddData untuk competition
    public function competitionData()
    {
        return $this->belongsTo(AddData::class, 'competition', 'competition');
    }

    // Relationship dengan AddData untuk competition_type
    public function competitionTypeData()
    {
        return $this->belongsTo(AddData::class, 'competition_type', 'competition_type');
    }

    // Relationship dengan AddData untuk series
    public function seriesData()
    {
        return $this->belongsTo(AddData::class, 'series', 'series');
    }

    // Relationship dengan AddData untuk phase
    public function phaseData()
    {
        return $this->belongsTo(AddData::class, 'phase', 'phase');
    }

    // ========== ACCESSOR DENGAN NULL SAFETY ==========
    
    // Accessor untuk mendapatkan pemenang dengan null safety
    public function getWinnerAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team1 ?? null;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team2 ?? null;
        }
        
        return null; // Seri
    }

    // Accessor untuk mendapatkan winner_id
    public function getWinnerIdAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team1_id;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team2_id;
        }
        
        return null; // Seri
    }

    // Accessor untuk mendapatkan loser_id
    public function getLoserIdAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team2_id;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team1_id;
        }
        
        return null; // Seri
    }

    // Accessor untuk mendapatkan status match
    public function getMatchStatusAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return ($this->team1->school_name ?? 'Team 1') . ' Menang';
        } elseif ($this->score_1 < $this->score_2) {
            return ($this->team2->school_name ?? 'Team 2') . ' Menang';
        }
        
        return 'Seri';
    }

    // Method untuk mengecek apakah bisa diedit
    public function getCanEditAttribute()
    {
        return $this->status !== 'done';
    }

    // Method untuk mengecek apakah bisa dipublish
    public function getCanPublishAttribute()
    {
        return $this->status !== 'done';
    }

    // Method untuk mengecek apakah bisa diunpublish
    public function getCanUnpublishAttribute()
    {
        return $this->status === 'publish';
    }

    // Method untuk mengecek apakah bisa di-mark as done
    public function getCanMarkAsDoneAttribute()
    {
        return in_array($this->status, ['draft', 'publish']);
    }

    // Method untuk mendapatkan nama file scoresheet
    public function getScoresheetFileNameAttribute()
    {
        if ($this->scoresheet_original_name) {
            return $this->scoresheet_original_name;
        }
        
        if ($this->scoresheet) {
            return basename($this->scoresheet);
        }
        
        return null;
    }

    // Method untuk mendapatkan path lengkap scoresheet
    public function getScoresheetPathAttribute()
    {
        if ($this->scoresheet) {
            return public_path($this->scoresheet);
        }
        
        return null;
    }

    // Method untuk mengecek apakah memiliki scoresheet
    public function getHasScoresheetAttribute()
    {
        if (empty($this->scoresheet)) {
            return false;
        }
        
        $path = public_path($this->scoresheet);
        return file_exists($path) && is_file($path);
    }

    // Method untuk mendapatkan format match date
    public function getFormattedMatchDateAttribute()
    {
        return $this->match_date ? $this->match_date->format('d F Y') : '-';
    }

    // Method untuk mendapatkan tanggal dalam format singkat
    public function getShortMatchDateAttribute()
    {
        return $this->match_date ? $this->match_date->format('d/m/Y') : '-';
    }

    // Method untuk mendapatkan score dalam format
    public function getScoreFormatAttribute()
    {
        return "{$this->score_1} - {$this->score_2}";
    }

    // Method untuk mendapatkan nama status dengan format
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50">Draft</span>',
            'publish' => '<span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-50">Published</span>',
            'done' => '<span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-50">Done</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary bg-opacity-10 text-secondary">Unknown</span>';
    }

    // Method untuk mendapatkan status text
    public function getStatusTextAttribute()
    {
        $texts = [
            'draft' => 'Draft',
            'publish' => 'Published',
            'done' => 'Done',
        ];
        
        return $texts[$this->status] ?? 'Unknown';
    }

    // Method untuk mengecek apakah hasil seri
    public function getIsDrawAttribute()
    {
        return $this->score_1 === $this->score_2;
    }

    // Method untuk mendapatkan total score
    public function getTotalScoreAttribute()
    {
        return $this->score_1 + $this->score_2;
    }

    // Method untuk mendapatkan selisih score
    public function getScoreDifferenceAttribute()
    {
        return abs($this->score_1 - $this->score_2);
    }

    // ========== METHOD BARU UNTUK TEAM DISPLAY ==========
    
    // Method untuk mendapatkan data display team1 dengan fallback icon
    public function getTeam1DisplayDataAttribute()
    {
        return $this->getTeamDisplayData($this->team1, 'team1');
    }

    // Method untuk mendapatkan data display team2 dengan fallback icon
    public function getTeam2DisplayDataAttribute()
    {
        return $this->getTeamDisplayData($this->team2, 'team2');
    }

    // Helper method untuk mendapatkan data display team
    protected function getTeamDisplayData($team, $teamType = 'team1')
    {
        if (!$team || !$team->school_name) {
            return [
                'id' => null,
                'name' => 'Team Not Found',
                'logo' => null,
                'logo_html' => '<div class="school-logo-placeholder">
                    <i class="fas fa-school text-secondary"></i>
                </div>',
                'display_html' => '<div class="d-flex align-items-center">
                    <div class="school-logo-placeholder me-2">
                        <i class="fas fa-school text-secondary"></i>
                    </div>
                    <span>Team Not Found</span>
                </div>',
                'has_logo' => false,
                'team_type' => $teamType
            ];
        }
        
        $hasLogo = !empty($team->school_logo);
        
        if ($hasLogo) {
            $logoHtml = '<img src="' . asset('uploads/school_logo/' . $team->school_logo) . '" 
                         alt="' . htmlspecialchars($team->school_name) . '" 
                         class="img-fluid rounded-circle school-logo"
                         style="width: 40px; height: 40px; object-fit: cover;">';
            
            $displayHtml = '<div class="d-flex align-items-center">
                <div class="school-logo me-2">
                    <img src="' . asset('uploads/school_logo/' . $team->school_logo) . '" 
                         alt="' . htmlspecialchars($team->school_name) . '" 
                         class="img-fluid rounded-circle"
                         style="width: 30px; height: 30px; object-fit: cover;">
                </div>
                <span>' . htmlspecialchars($team->school_name) . '</span>
            </div>';
        } else {
            $logoHtml = '<div class="school-logo-placeholder">
                <i class="fas fa-school text-secondary"></i>
            </div>';
            
            $displayHtml = '<div class="d-flex align-items-center">
                <div class="school-logo-placeholder me-2">
                    <i class="fas fa-school text-secondary"></i>
                </div>
                <span>' . htmlspecialchars($team->school_name) . '</span>
            </div>';
        }
        
        return [
            'id' => $team->team_id,
            'name' => $team->school_name,
            'logo' => $hasLogo ? asset('uploads/school_logo/' . $team->school_logo) : null,
            'logo_html' => $logoHtml,
            'display_html' => $displayHtml,
            'has_logo' => $hasLogo,
            'team_type' => $teamType
        ];
    }

    // Method untuk mendapatkan team logo HTML
    public function getTeam1LogoHtmlAttribute()
    {
        return $this->team1_display_data['logo_html'];
    }

    public function getTeam2LogoHtmlAttribute()
    {
        return $this->team2_display_data['logo_html'];
    }

    // Method untuk mendapatkan team display HTML
    public function getTeam1DisplayHtmlAttribute()
    {
        return $this->team1_display_data['display_html'];
    }

    public function getTeam2DisplayHtmlAttribute()
    {
        return $this->team2_display_data['display_html'];
    }

    // Method untuk mendapatkan nama team dengan fallback
    public function getTeam1NameAttribute()
    {
        return $this->team1->school_name ?? 'Team Not Found';
    }

    public function getTeam2NameAttribute()
    {
        return $this->team2->school_name ?? 'Team Not Found';
    }

    // Method untuk cek apakah team ada logo
    public function getTeam1HasLogoAttribute()
    {
        return !empty($this->team1->school_logo ?? null);
    }

    public function getTeam2HasLogoAttribute()
    {
        return !empty($this->team2->school_logo ?? null);
    }

    // ========== VALIDATION METHODS ==========
    
    // Validasi apakah kedua team berbeda
    public function validateDifferentTeams()
    {
        return $this->team1_id !== $this->team2_id;
    }

    // Validasi apakah scoresheet valid
    public function validateScoresheet()
    {
        if (empty($this->scoresheet)) {
            return true; // Tidak wajib
        }
        
        $path = public_path($this->scoresheet);
        if (!file_exists($path)) {
            return false;
        }
        
        $allowedExtensions = ['xlsx', 'xls', 'xlsm', 'xlsb', 'csv'];
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return in_array(strtolower($extension), $allowedExtensions);
    }

    // ========== BOOT METHOD ==========
    
    // Boot method untuk set default season jika kosong
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set default season jika kosong
            if (empty($model->season)) {
                $currentYear = date('Y');
                $model->season = "{$currentYear}/" . ($currentYear + 1);
            }
            
            // Set default status jika kosong
            if (empty($model->status)) {
                $model->status = 'draft';
            }

            // Validasi bahwa team1 dan team2 berbeda
            if ($model->team1_id === $model->team2_id) {
                throw new \Exception('Team 1 and Team 2 cannot be the same.');
            }
        });

        static::updating(function ($model) {
            // Jika status diubah menjadi done, pastikan tidak ada perubahan lagi
            if ($model->isDirty('status') && $model->status === 'done') {
                // Validasi bahwa scoresheet ada jika diperlukan
                // (opsional: bisa menambahkan validasi tambahan di sini)
            }

            // Validasi bahwa team1 dan team2 berbeda
            if ($model->isDirty(['team1_id', 'team2_id']) && $model->team1_id === $model->team2_id) {
                throw new \Exception('Team 1 and Team 2 cannot be the same.');
            }
        });
    }
}