{% extends '_templates/_template.php' %}

{% block content %}

{% include '_templates/_sub-header.php' %}

<div class="container">
    <br />
    <div class="row">
        <div class="not-found col-md-12">
            <h1>Oops 404 !</h1>
            <h2>La page que vous recherchez semble introuvable.</h2>
            <br />
            <p><a class="btn btn-info btn-lg" href="{{ baseUrl }}" role="button">Revenir à l'accueil</a></p>
        </div>
    </div>
</div>

{%endblock %}
