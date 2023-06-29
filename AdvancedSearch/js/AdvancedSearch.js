var LS = LS || {};
LS.plugin = LS.plugin || {};
LS.plugin.AdvancedSearch = LS.plugin.AdvancedSearch || {};


var csrfToken = $('input[name="YII_CSRF_TOKEN"]').val();


//modificar de aqui para abajo
LS.plugin.AdvancedSearch.load = function(links) {
    var getLink = links.getLink;
    var saveLink = links.saveLink;
    $.ajax({
        method: 'GET',
        url: getLink,
        data: {
            surveyId: $('#search-field').val(),

        }
    }).done(function(response) {
        var tokenInfo = JSON.parse(response);
        var template = '';
        $.each(tokenInfo, function(i, token) {
            template += `<tr>
                <td>${token.token}</td>
                <td>${token.firstname}</td>
                <td>${token.lastname}</td>
                <td>${token.email}</td>
                <td>${token.sid}</td>
                <td>${token.nameSurvey}</td>
              </tr>`
        })
        $('#data-container').html(template);
    });
};
LS.plugin.AdvancedSearch.loadData = function() {
    var links = {
        getLink: LS.plugin.AdvancedSearch.getSurveysLink,
    };
    LS.plugin.AdvancedSearch.load(links);
}