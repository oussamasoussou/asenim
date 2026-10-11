<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Coordonnées de l'entreprise : une seule fiche pour toute l'application.
 */
class CompanyInfo extends Model
{
    protected $table = 'company_infos';

    /**
     * Réseaux sociaux gérés : champ => [libellé, icône Font Awesome (site public), icône Boxicons (admin), domaines acceptés]
     */
    public const SOCIALS = [
        'facebook_url' => ['Facebook', 'fab fa-facebook-f', 'bxl-facebook-circle', ['facebook.com', 'fb.com', 'fb.me']],
        'linkedin_url' => ['LinkedIn', 'fab fa-linkedin-in', 'bxl-linkedin-square', ['linkedin.com', 'lnkd.in']],
        'youtube_url' => ['YouTube', 'fab fa-youtube', 'bxl-youtube', ['youtube.com', 'youtu.be']],
        'instagram_url' => ['Instagram', 'fab fa-instagram', 'bxl-instagram-alt', ['instagram.com', 'instagr.am']],
    ];

    protected $fillable = [
        'address',
        'phone',
        'email',
        'facebook_url',
        'linkedin_url',
        'youtube_url',
        'instagram_url',
        'updated_by',
    ];

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Retourne la fiche existante, ou une fiche vide non enregistrée.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static();
    }

    public function isEmpty(): bool
    {
        return !$this->address && !$this->phone && !$this->email && empty($this->socialLinks());
    }

    /**
     * Réseaux sociaux renseignés, dans l'ordre d'affichage.
     *
     * @return array<int, array{key: string, label: string, icon: string, admin_icon: string, url: string}>
     */
    public function socialLinks(): array
    {
        $links = [];
        foreach (self::SOCIALS as $field => [$label, $icon, $adminIcon]) {
            if ($this->{$field}) {
                $links[] = ['key' => str_replace('_url', '', $field), 'label' => $label, 'icon' => $icon, 'admin_icon' => $adminIcon, 'url' => $this->{$field}];
            }
        }

        return $links;
    }
}
