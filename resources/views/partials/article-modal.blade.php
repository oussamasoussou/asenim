{{-- Modale de lecture partagée : remplie depuis un <template data-article="ID"> rendu côté serveur. --}}
<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="articleModalTitle" data-article-title></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" data-article-body></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
