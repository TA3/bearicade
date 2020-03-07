var sk_color = "#191919";
var sk_element = document.createElement('div');
sk_element.className = "sk_content";
sk_element.innerHTML = "&nbsp;";
var sk_css = ".sk_content{display:block;width:100%;height:100%; min-height:20px;} .sk{min-width:100px; margin:auto;} .sk_content::before, .sk_content::after { content: ' '; display: block; background-image: linear-gradient( 110deg, #ececed 73%, #e9e9ea 75%, #ececed 77%, #ececed 78%, #e9e9ea 84%, #e9e9ea 88%, #ececed 94%, #ececed 100% ); background-size: 200% 100%; background-position: 0 center; border-radius: inherit; animation: 2s ease-in-out loading infinite; } /* Image placeholder. */ .sk_content::before { width: 100%; } /* Text placeholder. */ .sk_content::after { animation-delay: 50ms; width: 92%; height: 100%; margin-bottom:10px;  min-height: 20px; } @keyframes loading { 0% { background-position-x: 0; } 40%, 100% { background-position-x: -200%; } }";
var sk_elements = document.getElementsByClassName('sk');


/*------Add Style CSS------*/
var sk_head = document.head || document.getElementsByTagName('head')[0],
    sk_style = document.createElement('style');
sk_style.type = 'text/css';
if (sk_style.styleSheet){
  sk_style.styleSheet.cssText = sk_css;
} else {
  sk_style.appendChild(document.createTextNode(sk_css));
}
sk_head.appendChild(sk_style);



/*------Function------*/
function sk_init(){
for (var sk_i = 0; sk_i < sk_elements.length; sk_i++) {
    (function () {
        var sk_element_inner = sk_elements[sk_i].innerText;
        observeDOM( sk_elements[sk_i] ,sk_apply(sk_element_inner));
    }()); // immediate invocation
    
}


}


function sk_apply(sk_element_inner){ 
            if (sk_element_inner.innerText=="") {
                sk_element_inner.innerHTML = sk_element.outerHTML;
            }
            else if(sk_element_inner.innerText == sk_element.innerText.toString()){
                console.log('1st else if');
            }
            else if(sk_element_inner.innerText.includes(sk_element.innerText.toString())){
                console.log('2nd else if');
            }
            else {
                console.log(sk_element_inner.innerText);
            }
        }


var observeDOM = (function(){
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver,
        eventListenerSupported = window.addEventListener;

    return function(obj, callback){
        if( MutationObserver ){
            // define a new observer
            var obs = new MutationObserver(function(mutations, observer){
                if( mutations[0].addedNodes.length || mutations[0].removedNodes.length )
                    callback();
            });
            // have the observer observe foo for changes in children
            obs.observe( obj, { childList:true, subtree:true });
        }
        else if( eventListenerSupported ){
            obj.addEventListener('DOMNodeInserted', callback, false);
            obj.addEventListener('DOMNodeRemoved', callback, false);
        }
    };
})();

// Observe a specific DOM element:
