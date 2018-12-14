{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container" xmlns="http://www.w3.org/1999/html">
    {% include 'admin/_templates/_navigation.php' %}

    {% if filter == 'TRASH' %}
    <p><a href="{{ baseUrl }}/admin/comment/trash/purge" role="button" class="btn btn-danger btn-sm text-right confirm {% if amount_items.comment_trash == 0 %}disabled{% endif %}"><i class="fa fa-trash" aria-hidden="true"></i> Vider la corbeille</a></p>
    {% endif %}
    <div class="row">
        <table class="table table-hover">
            <thead class="thead-inverse">
            <tr>
                <th>{{ filter_title }}</th>
            </tr>
            </thead>
            <tbody>
            {% for comment in comments %}
            <tr {% if comment.report %}class="table-danger"{% endif %}>
                <td>
                    <h5>{{ comment.username  }}</h5>
                    <p><small>{{ comment.getFormatedDate(false) }}</small></p>
                    <p>{{ comment.content }}</p>
                    <p>
                        {% if not comment.isDelete %}
                        {% if comment.report %}
                        <a href="{{ baseUrl }}/admin/comment/{{ comment.id }}/unreported" role="button" class="btn btn-secondary btn-sm confirm"><i class="fa fa-hand-peace-o" aria-hidden="true"></i> Autoriser</a>
                        {% endif %}
                        <a href="{{ baseUrl }}/admin/comment/{{ comment.id }}/trash" role="button" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash" aria-hidden="true"></i> Corbeille</a>
                        {% else %}
                        <a href="{{ baseUrl }}/admin/comment/{{ comment.id }}/untrash" role="button" class="btn btn-secondary btn-sm confirm"><i class="fa fa-reply-all" aria-hidden="true"></i> Restaurer</a>
                        <a href="{{ baseUrl }}/admin/comment/delete/{{ comment.id }}" role="button" class="btn btn-danger btn-sm confirm"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</a>
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

