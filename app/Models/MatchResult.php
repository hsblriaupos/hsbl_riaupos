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
        'series', // Ditambahkan
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

    // Relationship dengan School untuk team1
    public function team1()
    {
        return $this->belongsTo(School::class, 'team1_id');
    }

    // Relationship dengan School untuk team2
    public function team2()
    {
        return $this->belongsTo(School::class, 'team2_id');
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

    // Accessor untuk mendapatkan pemenang
    public function getWinnerAttribute()
    {
        if ($this->score_1 > $this->score_2) {
            return $this->team1;
        } elseif ($this->score_1 < $this->score_2) {
            return $this->team2;
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
            return 'Team 1 Menang';
        } elseif ($this->score_1 < $this->score_2) {
            return 'Team 2 Menang';
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
        return !empty($this->scoresheet) && file_exists(public_path($this->scoresheet));
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
            'draft' => '<span class="badge bg-warning">Draft</span>',
            'publish' => '<span class="badge bg-success">Published</span>',
            'done' => '<span class="badge bg-primary">Done</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
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
        });

        static::updating(function ($model) {
            // Jika status diubah menjadi done, pastikan tidak ada perubahan lagi
            if ($model->isDirty('status') && $model->status === 'done') {
                // Log perubahan status ke done
                // (opsional: bisa menambahkan logging di sini)
            }
        });
    }
}