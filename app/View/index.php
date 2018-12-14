{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_full-sub-header.php' %}

<div class="container">
    <div class="row">
        {% for article in articles %}
        <article class="col-md-6">
            <div class="title-post"><h2>{{ article.title|upper  }}</h2></div>
            <ul class="meta-post">
                <li><span class="fa fa-calendar" aria-hidden="true"> </span> {{ article.getFormatedDate(false) }}</li>
                <li><span class="fa fa-comments-o" aria-hidden="true"> </span> {{ article.getAmountComments }} commentaire(s)</li>
            </ul>
            <p>{{ article.getExtract }}</p>
            <p><a class="btn btn-info" href="{{ article.getUrl }}" role="button">LIRE PLUS »</a></p>
        </article>
        {% endfor %}
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
