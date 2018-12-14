{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container">
    {% include 'admin/_templates/_navigation.php' %}
    {% if message %}<div class="alert alert-success" role="alert"><i class="fa fa-check-circle"></i> {{ message }}</div>{% endif %}
    <br />
    <p>Cet espace privé vous offre la possibilité d'administrer très facilement votre site.</p>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-pencil-square-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 pannel-right text-right">
                            <div class="huge">{{amount_items.article_untrash}}</div>
                            <div>Article(s)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 pannel-right text-right">
                            <div class="huge">{{amount_items.comment_untrash}}</div>
                            <div>Commentaire(s)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 pannel-right text-right">
                            <div class="huge">{{amount_items.users}}</div>
                            <div>Utilisateur(s)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}