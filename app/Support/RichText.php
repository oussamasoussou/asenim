<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Texte riche saisi avec la barre d'outils (éditeur Quill).
 *
 *  - sanitize()    : n'accepte qu'une liste blanche de balises, supprime tout le reste (anti-XSS)
 *  - render()      : HTML sûr à afficher ; les anciens textes bruts sont convertis en paragraphes
 *  - toPlainText() : version texte (extraits, balise meta description…)
 */
class RichText
{
    /** Balises autorisées → attributs autorisés */
    private const ALLOWED = [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'h2' => [], 'h3' => [], 'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [],
        'a' => ['href'],
    ];

    /** Balises dont le contenu entier est supprimé */
    private const DROP_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'template', 'noscript', 'svg', 'math', 'form', 'input', 'button', 'select', 'textarea'];

    public static function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim(str_replace("\u{00A0}", ' ', $html));
        if ($html === '') {
            return null;
        }

        // Ancien texte brut (sans balise) : conservé tel quel, il sera mis en forme à l'affichage
        if (!self::isHtml($html)) {
            return $html;
        }

        $doc = self::load($html);
        $root = $doc->getElementById('rt-root');
        self::clean($root);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $doc->saveHTML($child);
        }

        $out = trim($out);
        // Éditeur vide (« <p><br></p> ») → aucune valeur
        return self::toPlainText($out) === '' ? null : $out;
    }

    public static function render(?string $content): string
    {
        $content = (string) $content;
        if (trim($content) === '') {
            return '';
        }

        if (!self::isHtml($content)) {
            // Texte brut historique : un paragraphe par ligne
            return collect(preg_split('/\R+/', trim($content)))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->map(fn ($line) => '<p>' . e($line) . '</p>')
                ->implode('');
        }

        return (string) self::sanitize($content);
    }

    public static function toPlainText(?string $content): string
    {
        $content = (string) $content;
        // Les fins de blocs deviennent des espaces pour ne pas coller les mots
        $content = preg_replace('/<\s*(br|\/p|\/li|\/h[1-6]|\/blockquote)\b[^>]*>/i', ' ', $content);
        $text = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', str_replace("\u{00A0}", ' ', $text)));
    }

    public static function excerpt(?string $content, int $limit = 150): string
    {
        return \Illuminate\Support\Str::limit(self::toPlainText($content), $limit);
    }

    public static function isHtml(string $content): bool
    {
        return (bool) preg_match('/<\s*\/?\s*[a-z][a-z0-9]*\b[^>]*>/i', $content);
    }

    private static function load(string $html): DOMDocument
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"?><div id="rt-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $doc;
    }

    private static function clean(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);

                if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                    $node->removeChild($child);
                    continue;
                }

                self::clean($child);

                if (!array_key_exists($tag, self::ALLOWED)) {
                    // Balise non autorisée : on garde son contenu, sans la balise
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);
                    continue;
                }

                foreach (iterator_to_array($child->attributes) as $attribute) {
                    if (!in_array($attribute->name, self::ALLOWED[$tag], true)) {
                        $child->removeAttribute($attribute->name);
                    }
                }

                if ($tag === 'a') {
                    $href = trim($child->getAttribute('href'));
                    if (!preg_match('#^(https?://|mailto:|tel:|/)#i', $href)) {
                        $child->removeAttribute('href'); // javascript:, data:, etc.
                    } else {
                        $child->setAttribute('rel', 'noopener nofollow');
                        if (preg_match('#^https?://#i', $href)) {
                            $child->setAttribute('target', '_blank');
                        }
                    }
                }
            } elseif ($child->nodeType === XML_COMMENT_NODE || $child->nodeType === XML_PI_NODE) {
                $node->removeChild($child);
            }
        }
    }
}
