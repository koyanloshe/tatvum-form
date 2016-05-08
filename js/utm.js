$(function(){
        function getParameterByName(name)
        {
                name            = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
                var regexS      = "[\\?&]" + name + "=([^&#]*)";
                var regex       = new RegExp(regexS);
                var results     = regex.exec(window.location.search);
                if (results == null)
                {
                        return "";
                }
                else 
                {
                        return decodeURIComponent(results[1].replace(/\+/g, " "));
                }
        }
        function addFormElem(paramName, fieldName)
        {
                var paramValue  = getParameterByName(paramName);
                var $utmEl      = $("<input type='hidden' name='" + fieldName + "' value='" + paramValue + "'>");
                if (paramValue != "")
                {
                        $("form").first().prepend($utmEl);
                }
        }
        var utmParams = {
                "utm_source"    : "utm[Source]",
                "utm_medium"    : "utm[Medium]",
                "utm_campaign"  : "utm[Campaign]",
                "utm_content"   : "utm[Content]",
                "utm_term"      : "utm[Term]"
        };
        for (var param in utmParams)
        {
                addFormElem(param, utmParams[param]);
        }
});