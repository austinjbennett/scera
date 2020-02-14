var announcement_container = document.createElement('div')

function _add_announcement(announcement){
  announcement_container.innerHTML = announcement
}

/* add lastpass class so that username/password icons can be removed if lastpass is enabled as they interfere with lastpass */
function addClassIfLastPassEnabled(){
    if ( document.querySelector('#loginform>p input[style^="background-image"]') ){
        document.getElementById('loginform').classList.add('lastpass-installed')
    }
}

/*
  Fetch branding info.
*/
document.addEventListener("DOMContentLoaded", function() {

  // Do not load announcements on smaller devices
  if (documentWidth() < 1000){
    return;
  }

  announcement_container.className = 'left announcement-container'

  var login_container = document.createElement('div')
  login_container.className = 'right login-container'
  login_container.appendChild( document.getElementById('login') )

  var leftright = document.createElement('div')
  leftright.className = 'left-right-container'
  leftright.appendChild(login_container)
  leftright.appendChild(announcement_container)

  document.body.appendChild(leftright)


  domain = window.location.hostname.replace(/^www./, '')
  fetch('https://announcements.wordpressoverwatch.com/v1/clients.php?site='+domain)
  .then(
    function(response) {
      if (response.status !== 200) {
        console.log('Failed to fetch announcements. Status Code: ' +
          response.status);
        console.log(response)
        return;
      }

      // Examine the text in the response
      response.text().then(function(data) {
        add_branding_info(data);
      });
    }
  )
  .catch(function(err) {
    console.log('Fetch Error :', err);
  });


    /* If LastPass renders the password icons on the inputs, then add a LastPass class for our CSS */
    var lpObserver = new MutationObserver(function() { addClassIfLastPassEnabled() })
    lpObserver.observe(document.querySelector('#loginform>p input'), {
        attributes: true
    });
    /* Also perform a check immediately for LastPass because it may have already loaded */
    addClassIfLastPassEnabled();

});


function add_branding_info(page){

    formData = new FormData();
    formData.append("action", "ow_announcement_branding");
    formData.append("security", ow.nonce);
    formData.append("content", page);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', ow.ajax_url + '?action=ow_announcement_branding', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        _add_announcement(this.responseText);
    };
    //xhr.send(formData);
    xhr.send('action=ow_announcement_branding' + '&security='+ow.nonce + '&content='+encodeURIComponent(page));

    // fetch( ow.ajax_url + '?action=ow_announcement_branding', {
    //   method: 'POST',
    //   headers : new Headers({'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'}),
    //   //credentials: 'same-origin',
    //   // body: formData
    //   body: 'action=ow_announcement_branding' + '&security='+ow.nonce + '&content='+encodeURIComponent(page)
    //   //body: JSON.stringify({'action': 'ow_announcement_branding', 'security':ow.nonce, 'content': page})
    //   //body: searchParams
    // } )
    //   .then(
    //     function(response) {
    //       if (response.status !== 200) {
    //         console.log('Looks like there was a problem. Status Code: ' +
    //           response.status);
    //         return;
    //       }
    //
    //       // Examine the text in the response
    //       response.text().then(function(data) {
    //         _add_announcement(data);
    //       });
    //     }
    //   )
    //   .catch(function(err) {
    //     console.log('Fetch Error :-S', err);
    //   });
}

function documentWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}
