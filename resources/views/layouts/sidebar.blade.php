<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">

      </span>
      <img src="https://www.affinicia.com/wp-content/themes/affinicia/images/logos/affinicia.svg" alt="Logo"
        style="width:170px" />
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>
  <hr>

  <ul class="menu-inner py-1">
    <li class="menu-item">
      <a href="{{ route('users.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Account Settings">Utilisateurs</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="{{ route('documents.non_archived') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bxs-file-doc"></i>
        <div data-i18n="Suppliers">Documents</div>
      </a>
    </li>
  </ul>
</aside>