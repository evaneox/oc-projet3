<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Bienvenue dans votre Tableau de bord
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <br />
    <div class="col-md-12">
        <!-- Menu de navigation admin -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a {% if page == 'index' %}class="nav-link active"{% else %} class="nav-link" {% endif %} href="{{ baseUrl }}/admin">Accueil</a>
            </li>
            <li class="nav-item dropdown">
                <a {% if page == 'article' %}class="nav-link dropdown-toggle active"{% else %}class="nav-link dropdown-toggle"{% endif %} data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Articles</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/article/add">Écrire un article</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/articles">Tous ({{amount_items.article_untrash}})</a>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/articles&amp;filter=published">Publiés ({{amount_items.article_published}})</a>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/articles&amp;filter=unpublished">Non publiés ({{amount_items.article_unpublished}})</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/articles&amp;filter=trash">Corbeille ({{amount_items.article_trash}})</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a {% if page == 'comment' %}class="nav-link dropdown-toggle active"{% else %}class="nav-link dropdown-toggle"{% endif %} data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Commentaires</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/comments">Tous ({{amount_items.comment_untrash}})</a>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/comments&amp;filter=reported">Signalés ({{amount_items.comment_report}})</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ baseUrl }}/admin/list/comments&amp;filter=trash">Corbeille ({{amount_items.comment_trash}})</a>
                </div>
            </li>
        </ul>
    </div>
</div>
<br />

