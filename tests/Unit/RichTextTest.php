<?php

namespace Tests\Unit;

use App\Support\RichText;
use PHPUnit\Framework\TestCase;

class RichTextTest extends TestCase
{
    public function test_keeps_toolbar_formatting(): void
    {
        $html = '<h2>Titre</h2><p><strong>Gras</strong>, <em>italique</em>, <u>souligné</u>, <s>barré</s></p>'
            . '<ul><li>Un</li><li>Deux</li></ul><ol><li>A</li></ol><blockquote>Citation</blockquote>';

        $this->assertSame($html, RichText::sanitize($html));
    }

    public function test_removes_dangerous_markup(): void
    {
        $clean = RichText::sanitize(
            '<p onclick="alert(1)" style="color:red" class="x">Texte</p>'
            . '<script>alert(1)</script><style>p{}</style><iframe src="//evil"></iframe>'
            . '<img src="x" onerror="alert(1)"><a href="javascript:alert(1)">piège</a>'
            . '<div><span>contenu gardé</span></div><!-- commentaire -->'
        );

        $this->assertStringNotContainsStringIgnoringCase('script', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onclick', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onerror', $clean);
        $this->assertStringNotContainsStringIgnoringCase('javascript:', $clean);
        $this->assertStringNotContainsString('<img', $clean);
        $this->assertStringNotContainsString('<iframe', $clean);
        $this->assertStringNotContainsString('style=', $clean);
        $this->assertStringNotContainsString('commentaire', $clean);
        $this->assertStringContainsString('<p>Texte</p>', $clean);
        $this->assertStringContainsString('contenu gardé', $clean); // balises inconnues retirées, texte conservé
        $this->assertStringContainsString('<a>piège</a>', $clean);
    }

    public function test_safe_links_open_in_new_tab(): void
    {
        $this->assertSame(
            '<p><a href="https://enim.rnu.tn" rel="noopener nofollow" target="_blank">ENIM</a></p>',
            RichText::sanitize('<p><a href="https://enim.rnu.tn" title="x">ENIM</a></p>')
        );
        $this->assertStringContainsString('href="mailto:contact@asenim.tn"', RichText::sanitize('<p><a href="mailto:contact@asenim.tn">écrire</a></p>'));
    }

    public function test_empty_editor_is_stored_as_null(): void
    {
        $this->assertNull(RichText::sanitize('<p><br></p>'));
        $this->assertNull(RichText::sanitize('   '));
        $this->assertNull(RichText::sanitize(null));
    }

    public function test_legacy_plain_text_is_rendered_as_paragraphs_and_escaped(): void
    {
        $this->assertSame("Ligne 1\nLigne <2>", RichText::sanitize("Ligne 1\nLigne <2>"));
        $this->assertSame('<p>Ligne 1</p><p>Ligne &lt;2&gt;</p>', RichText::render("Ligne 1\n\nLigne <2>"));
    }

    public function test_plain_text_excerpt(): void
    {
        $html = '<h2>Titre</h2><p>Premier&nbsp;paragraphe avec <strong>gras</strong> &amp; accents é.</p><ul><li>Un</li><li>Deux</li></ul>';
        $this->assertSame('Titre Premier paragraphe avec gras & accents é. Un Deux', RichText::toPlainText($html));
        $this->assertSame('Titre Premier p...', RichText::excerpt($html, 15));
    }
}
