
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        
        if (response.status === 'connected') {
            testAPI(response.authResponse['accessToken']);
        } else {
            document.getElementById('status').innerHTML = 'Please log into this app.';
        }
    }
    
    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });
    }
    
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '462326684162348',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.11'
        });
        
        FB.getLoginStatus(function(response) {
          statusChangeCallback(response);
        });
    };
  
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.11&appId=462326684162348';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    
    function testAPI(accessToken) {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', {fields: 'first_name, last_name, email'}, function(response) {
            //ユーザー登録に必要な情報を取得
            var name = response.first_name + ' ' + response.last_name;
            var email = response.email;
            // TODO:Illuminateを呼び出しFacebookでログインでもDBに保存するように
            //      ajaxで飛ばせるか試してみたが現状422エラー発生
            // $.ajax({
            //     url: '/register',
            //     type: 'POST',
            //     dataType: 'json',
            //     data: {name: name, email: email, password: accessToken}
            // })
            // .done(function() {
            //     console.log("success");
            // })
            // .fail(function() {
            //     console.log("error");
            // })
            // .always(function() {
            //     console.log("complete");
            // });
            // 
            document.getElementById('status').innerHTML =
            'Thanks for logging in, ' + name + '!';
        });
    }