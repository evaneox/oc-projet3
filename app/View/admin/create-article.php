{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container">
    {% include 'admin/_templates/_navigation.php' %}
    <div class="row">
        <div class="col-md-12">
            <h3>Ajouter un nouvel article</h3>
            <br />
            <form id="articleAdd" role="form" method="post" action="{{ baseUrl}}/admin/article/add">
                <div class="form-group">
                    {% if errors.title %}<div class="alert alert-danger" role="alert">{{ errors.title }}</div>{% endif %}
                    <input type="text" class="form-control" name="title" placeholder="Saisissez votre titre ici" required="required" autocomplete="off" />
                </div>
                    <div class="form-group">
                        {% if errors.content %}<div class="alert alert-danger" role="alert">{{ errors.content }}</div>{% endif %}
                        <textarea id="tiny-mce" class="form-control" name="content"  novalidate></textarea>
                    </div>
                    <br />
                <div class="form-group">
                    <input type="checkbox" name="publish"/> Publié cet article<br /><br />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-info" name="save" value="Enregistrer" />
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}