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
        'match_data_id', // Tambahkan untuk relationship dengan MatchData
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

    // ========== RELATIONSHIPS ==========

    // Relationship dengan MatchData
    public function matchData()
    {
        return $this->belongsTo(MatchData::class, 'match_data_id');
    }

    // Relationship dengan TeamList untuk team1
    public function team1()
    {
        return $this->belongsTo(TeamList::class, 'team1_id', 'team_id')
                    ->withDefault([
                        'team_id' => null,
                        'school_name' => 'Team A',
                        'school_logo' => null
                    ]);
    }

    // Relationship dengan TeamList untuk team2
    public function team2()
    {
        return $this->belongsTo(TeamList::class, 'team2_id', 'team_id')
                    ->withDefault([
                        'team_id' => null,
                        'school_name' => 'Team B',
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

    // ========== SCOPES ==========

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

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
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

    public function scopeUpcomingMatches($query)
    {
        return $query->whereIn('status', ['scheduled', 'upcoming'])
                     ->whereDate('match_date', '>=', now())
                     ->orderBy('match_date', 'asc');
    }

    public function scopeRecentMatches($query, $limit = 10)
    {
        return $query->where('status', 'completed')
                     ->orderBy('match_date', 'desc')
                     ->limit($limit);
    }

    public function scopeTodayMatches($query)
    {
        return $query->whereDate('match_date', today())
                     ->orderBy('match_date', 'asc');
    }

    // ========== ACCESSORS ==========

    // Accessor untuk mendapatkan pemenang
    public function getWinnerAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team1 ?? null;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team2 ?? null;
        }
        
        return null; // Seri
    }

    // Accessor untuk winner_id
    public function getWinnerIdAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team1_id;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team2_id;
        }
        
        return null; // Seri
    }

    // Accessor untuk loser_id
    public function getLoserIdAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team2_id;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team1_id;
        }
        
        return null; // Seri
    }

    // Accessor untuk match status
    public function getMatchStatusAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return ($this->team1_name) . ' Menang';
        } elseif ($this->score_1 < $this->score_2) {
            return ($this->team2_name) . ' Menang';
        }
        
        return 'Seri';
    }

    // Accessor untuk nama team1
    public function getTeam1NameAttribute()
    {
        return $this->team1->school_name ?? 'Team A';
    }

    // Accessor untuk nama team2
    public function getTeam2NameAttribute()
    {
        return $this->team2->school_name ?? 'Team B';
    }

    // Accessor untuk logo team1
    public function getTeam1LogoAttribute()
    {
        return $this->team1->school_logo ?? null;
    }

    // Accessor untuk logo team2
    public function getTeam2LogoAttribute()
    {
        return $this->team2->school_logo ?? null;
    }

    // Accessor untuk formatted match date
    public function getFormattedMatchDateAttribute()
    {
        return $this->match_date ? $this->match_date->format('d F Y') : '-';
    }

    // Accessor untuk short match date
    public function getShortMatchDateAttribute()
    {
        return $this->match_date ? $this->match_date->format('d/m/Y') : '-';
    }

    // Accessor untuk match time
    public function getMatchTimeAttribute()
    {
        return $this->match_date ? $this->match_date->format('H:i') : '-';
    }

    // Accessor untuk score format
    public function getScoreFormatAttribute()
    {
        return "{$this->score_1} - {$this->score_2}";
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'publish' => '<span class="badge bg-success">Published</span>',
            'done' => '<span class="badge bg-primary">Done</span>',
            'scheduled' => '<span class="badge bg-info">Scheduled</span>',
            'upcoming' => '<span class="badge bg-warning">Upcoming</span>',
            'live' => '<span class="badge bg-danger">Live</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-dark">Unknown</span>';
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        $texts = [
            'draft' => 'Draft',
            'publish' => 'Published',
            'done' => 'Done',
            'scheduled' => 'Scheduled',
            'upcoming' => 'Upcoming',
            'live' => 'Live',
            'completed' => 'Completed',
        ];
        
        return $texts[$this->status] ?? 'Unknown';
    }

    // Accessor untuk cek apakah seri
    public function getIsDrawAttribute()
    {
        return $this->score_1 === $this->score_2;
    }

    // Accessor untuk total score
    public function getTotalScoreAttribute()
    {
        return $this->score_1 + $this->score_2;
    }

    // Accessor untuk score difference
    public function getScoreDifferenceAttribute()
    {
        return abs($this->score_1 - $this->score_2);
    }

    // Accessor untuk cek apakah bisa diedit
    public function getCanEditAttribute()
    {
        return in_array($this->status, ['draft', 'publish', 'scheduled', 'upcoming']);
    }

    // Accessor untuk cek apakah bisa dipublish
    public function getCanPublishAttribute()
    {
        return in_array($this->status, ['draft', 'scheduled', 'upcoming']);
    }

    // Accessor untuk cek apakah bisa diunpublish
    public function getCanUnpublishAttribute()
    {
        return $this->status === 'publish';
    }

    // Accessor untuk cek apakah bisa di-mark as done
    public function getCanMarkAsDoneAttribute()
    {
        return in_array($this->status, ['draft', 'publish', 'completed']);
    }

    // Accessor untuk scoresheet file name
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

    // Accessor untuk scoresheet path
    public function getScoresheetPathAttribute()
    {
        if ($this->scoresheet) {
            return public_path('uploads/scoresheets/' . $this->scoresheet);
        }
        
        return null;
    }

    // Accessor untuk cek apakah memiliki scoresheet
    public function getHasScoresheetAttribute()
    {
        if (empty($this->scoresheet)) {
            return false;
        }
        
        $path = $this->scoresheet_path;
        return file_exists($path) && is_file($path);
    }

    // Accessor untuk scoresheet URL
    public function getScoresheetUrlAttribute()
    {
        if ($this->has_scoresheet) {
            return asset('uploads/scoresheets/' . $this->scoresheet);
        }
        return null;
    }

    // Accessor untuk venue (dari match_data jika ada)
    public function getVenueAttribute()
    {
        if ($this->matchData && $this->matchData->venue) {
            return $this->matchData->venue;
        }
        return 'TBD';
    }

    // Accessor untuk match title (dari match_data jika ada)
    public function getMatchTitleAttribute()
    {
        if ($this->matchData && $this->matchData->main_title) {
            return $this->matchData->main_title;
        }
        return "{$this->team1_name} vs {$this->team2_name}";
    }

    // ========== METHODS ==========

    // Method untuk validasi teams berbeda
    public function validateDifferentTeams()
    {
        return $this->team1_id !== $this->team2_id;
    }

    // Method untuk validasi scoresheet
    public function validateScoresheet()
    {
        if (empty($this->scoresheet)) {
            return true; // Tidak wajib
        }
        
        $path = $this->scoresheet_path;
        if (!file_exists($path)) {
            return false;
        }
        
        $allowedExtensions = ['xlsx', 'xls', 'xlsm', 'xlsb', 'csv', 'pdf'];
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return in_array(strtolower($extension), $allowedExtensions);
    }

    // Method untuk mendapatkan match status untuk display
    public function getDisplayStatus()
    {
        $statusMap = [
            'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-800'],
            'publish' => ['label' => 'Published', 'class' => 'bg-green-100 text-green-800'],
            'done' => ['label' => 'Done', 'class' => 'bg-blue-100 text-blue-800'],
            'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-yellow-100 text-yellow-800'],
            'upcoming' => ['label' => 'Upcoming', 'class' => 'bg-orange-100 text-orange-800'],
            'live' => ['label' => 'Live', 'class' => 'bg-red-100 text-red-800'],
            'completed' => ['label' => 'Completed', 'class' => 'bg-green-100 text-green-800'],
        ];

        return $statusMap[$this->status] ?? ['label' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }

    // Method untuk update score
    public function updateScore($score1, $score2)
    {
        $this->score_1 = $score1;
        $this->score_2 = $score2;
        
        // Jika ada score, ubah status menjadi completed
        if ($score1 !== null && $score2 !== null) {
            $this->status = 'completed';
        }
        
        return $this->save();
    }

    // ========== BOOT METHOD ==========

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
            }

            // Validasi bahwa team1 dan team2 berbeda
            if ($model->isDirty(['team1_id', 'team2_id']) && $model->team1_id === $model->team2_id) {
                throw new \Exception('Team 1 and Team 2 cannot be the same.');
            }
        });
    }
}