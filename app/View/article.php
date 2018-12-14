{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container">

    <!-- Article -->
    <div class="row">
        <article class="col-md-12">
            <div class="title-post"><h2>{{ article.title|upper  }}</h2></div>
            <ul class="meta-post">
                <li><span class="fa fa-calendar" aria-hidden="true"> </span> {{ article.getFormatedDate(false) }}</li>
                <li><span class="fa fa-user-circle-o" aria-hidden="true"> </span> &nbsp;Jean Forteroche</li>
            </ul>
            <p class="beauty">{{ article.content|raw }}</p>
        </article>
    </div>

    <!-- Commentaires -->
    <div class="row">
        <div class="separator col-md-12"><span class="fa fa-comments-o" aria-hidden="true" ></span> {{ article.getAmountComments }} commentaire(s)</div>
        {% if article.getAmountComments == 0 %}<p class="beauty">Soyez le premier à publier un commentaire !</p>{% endif %}
        {% for comment in article.comments %}
        <div id="comment-{{ comment.id }}" class="col-md-12">
            <div class="comment niv-{{ comment.level }}">
                <div class="comment-body">
                    <ul class="comment-heading">
                        <h4>{{ comment.username }}</h4>
                        <li class="time"><span class="fa fa-clock-o" aria-hidden="true"> </span> {{ comment.getFormatedDate }}</li>
                    </ul>
                    {% if not comment.isDelete %}
                    <div class="comment-controls">
                        {% if comment.level < 3 %}
                        <a class="reply" href="#"><i class="fa fa-reply" aria-hidden="true"></i></a>
                        <a class="cancel-reply" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                        {% endif %}
                        {% if not comment.report %}
                        <a class="report" href="{{ baseUrl}}/comment/report/{{ comment.id }}"><i class="fa fa-flag" aria-hidden="true"></i></a>
                        {% endif %}
                    </div>
                    <p>{{ comment.content }}</p>
                    {% else %}
                    <p><em>** Ce commentaire a été supprimer par un administrateur **</em></p>
                    {% endif %}
                </div>
            </div>
            <!-- Formulaire de reponse -->
            {% if comment.level < 3 %}
            <div class="col-md-8 sub-content-form">
                <div class="well">
                    <form class="comment-form" role="form" method="post" action="{{ baseUrl}}/comment/add/{{ article.id }}">
                        {% if not auth %}
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="VOTRE NOM  *" required="required" />
                        </div>
                        {% endif %}
                        <div class="form-group">
                            <textarea class="form-control" name="content" rows="3" placeholder="VOTRE MESSAGE  *" required="required" ></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" value="REPONDRE" />
                            <input type="hidden" name="parent" value="{{ comment.id }}" />
                            {% if auth %}
                            <input type="hidden" name="name" value="{{ auth.username }}" />
                            {% endif %}
                        </div>
                    </form>
                </div>
            </div>
            {% endif %}
        </div>
        {% endfor %}
    </div>
    <br />
    <!-- Formulaire -->
    <div class="row">
        <div id="content-form" class="col-md-8">
            <div class="well">
                <h4>Laissez un commentaire :</h4>
                <p><small>Les champs (*) sont requis.</small></p>
                <form class="comment-form" role="form" method="post" action="{{ baseUrl}}/comment/add/{{ article.id }}">
                    {% if not auth %}
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="VOTRE NOM  *" required="required" />
                    </div>
                    {% endif %}
                    <div class="form-group">
                        <textarea class="form-control" name="content" rows="3" placeholder="VOTRE COMMENTAIRE  *" required="required" ></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-info" value="PUBLIER COMMENTAIRE" />
                        {% if auth %}
                        <input type="hidden" name="name" value="{{ auth.username }}" />
                        {% endif %}
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
{% endblock %}
