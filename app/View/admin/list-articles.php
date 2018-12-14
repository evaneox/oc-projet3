{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container" xmlns="http://www.w3.org/1999/html">
    {% include 'admin/_templates/_navigation.php' %}

    {% if filter == 'TRASH' %}
    <p><a href="{{ baseUrl }}/admin/article/trash/purge" role="button" class="btn btn-danger btn-sm text-right confirm {% if amount_items.article_trash == 0 %}disabled{% endif %}"><i class="fa fa-trash" aria-hidden="true"></i> Vider la corbeille</a></p>
    {% endif %}
    <div class="row">
        <table class="table table-hover">
            <thead class="thead-inverse">
            <tr>
                <th>{{ filter_title }}</th>
            </tr>
            </thead>
            <tbody>
            {% for article in articles %}
            <tr>
                <td>
                    <h5>{{ article.title|upper  }}</h5>
                    <p><small>{{ article.getFormatedDate(false) }}</small></p>
                    <p>{{ article.getExtract(100) }}</p>
                    <p>
                        <a href="{{ article.getUrl}}" target="_blank" role="button" class="btn btn-secondary btn-sm"><i class="fa fa-link" aria-hidden="true"></i> Voir</a>
                        {% if not article.isDelete %}
                        <a href="{{ baseUrl }}/admin/article/edit/{{ article.id }}" role="button" class="btn btn-secondary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> Éditer</a>
                        {% if not article.isPublished %}
                        <a href="{{ baseUrl }}/admin/article/{{ article.id }}/published" role="button" class="btn btn-secondary btn-sm confirm"><i class="fa fa-eye" aria-hidden="true"></i> Publier</a>
                        {% else %}
                        <a href="{{ baseUrl }}/admin/article/{{ article.id }}/unpublished" role="button" class="btn btn-secondary btn-sm confirm"><i class="fa fa-eye-slash" aria-hidden="true"></i> Dé-Publier</a>
                        {% endif %}
                        <a href="{{ baseUrl }}/admin/article/{{ article.id }}/trash" role="button" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash" aria-hidden="true"></i> Corbeille</a>
                        {% else %}
                        <a href="{{ baseUrl }}/admin/article/{{ article.id }}/untrash" role="button" class="btn btn-secondary btn-sm confirm"><i class="fa fa-reply-all" aria-hidden="true"></i> Restaurer</a>
                        <a href="{{ baseUrl }}/admin/article/delete/{{ article.id }}" role="button" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</a>
                        {% endif %}
                    </p>
                </td>
            </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>


    <div class="row">
        <br />
        {% if pagination.numPages > 1 %}
        <div class="col-md-12">
            <ul class="pagination">
                {% if pagination.prevUrl %}
                <li class="page-item">
                    <a class="page-link" href="{{ pagination.prevUrl }}">Précédent</a>
                </li>
                {% endif %}
                {% for page in pagination.pages %}
                {% if page.url %}
                {% if page.isCurrent %}
                <li class="page-item active">
                    <span class="page-link">{{ page.num }}</span>
                </li>
                {% else %}
                <li class="page-item">
                    <a class="page-link" href="{{ page.url }}">{{ page.num }}</a>
                </li>
                {% endif %}
                {% else %}
                <li class="page-item disabled">
                    <span class="page-link">{{ page.num }}</span>
                </li>
                {% endif %}
                {% endfor %}
                {% if pagination.nextUrl %}
                <li class="page-item">
                    <a class="page-link" href="{{ pagination.nextUrl }}">Suivant</a>
                </li>
                {% endif %}
            </ul>
        </div>
        {% endif %}
    </div>

</div>
{% endblock %}
