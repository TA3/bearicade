
function initLoader(element, customMessage) {
    if (typeof customMessage === 'undefined') {
        if (typeof element === 'undefined') {
            if ($('#loaderElement').length) {
                $('#loaderElement').fadeOut(300, function() {
                    $('#loaderElement').remove();
                })
                return;
            }
        }
        if ($('#loaderElement').length) {
            $('#loaderElement').fadeOut(100, function() {
                $('#loaderElement').remove();
            })
        }
            var loaderElement = document.createElement("div");
            loaderElement.id = "loaderElement";
            loaderElement.style = `display: none; position: absolute; background: #191919; width: calc(100% - 20px); height: 100%; z-index: 1; margin: auto; top: 0; left: 0; right: 0; bottom: 0; border-radius:5px;`;
            paddingLeft = ($(element).outerWidth() / 2) - 15 + "px";
            paddingTop = ($(element).outerHeight() / 2) - 12 + "px";
            loaderElement.style.paddingLeft = paddingLeft;
            loaderElement.style.paddingTop = paddingTop;
            loaderElement.innerHTML = `<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve"> <rect x="0" y="9.37643" width="4" height="11.2471" fill="#d9c285" opacity="0.2"> <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate> </rect> <rect x="8" y="6.87643" width="4" height="16.2471" fill="#d9c285" opacity="0.2"> <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate> </rect> <rect x="16" y="5.62357" width="4" height="18.7529" fill="#d9c285" opacity="0.2"> <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate> <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate> </rect> </svg>`;
            $(element).append(loaderElement);
            $("#loaderElement").fadeIn(300);
    }
    else{
        $('#loaderElement').remove();
        var loaderElement = document.createElement("div");
            loaderElement.id = "loaderElement";
            loaderElement.style = `text-align:center;display: none; position: absolute; background: #191919; width: calc(100% - 20px); height: 100%; z-index: 1; margin: auto; top: 0; left: 0; right: 0; bottom: 0; border-radius:5px;`;
            paddingTop = ($(element).outerHeight() / 2) - 12 + "px";
            loaderElement.style.paddingTop = paddingTop;
            loaderElement.innerHTML = customMessage;
            $(element).append(loaderElement);
            $("#loaderElement").fadeIn(300);
    }
    
    

}
