
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        
        if (response.status === 'connected') {
            document.getElementById('status').innerHTML = 'Now Login.';
        } else {
            document.getElementById('status').innerHTML = 'Please log into this app.';
        }
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
    
    function useFacebookLogin() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', {fields: 'name, email'}, function(response) {
            //ユーザー登録に必要な情報を取得
            var name = response.name;
            var email = response.email;
            var location
            /*
             * TODO:: 今はログインボタンの挙動を確認するために
             *         confirm_facebookではAuthに関係なく表示できるDisplayに遷移するように
             *         response値を設定しているが、本来遷移すべきはAuthによる判別が
             *         必要なProductであるため、ajax先でユーザーの新規登録及び確認が必要。
             *         
             */
            $.ajax({
                url: '/confirm_facebook',
                type: 'GET',
                dataType: 'json',
                data: {name: name, email: email}
            })
            .done(function(e) {
                console.log("success");
                location = e;
            })
            .fail(function(e) {
                console.log("error");
            })
            .always(function(e) {
                console.log("complete");  
                if (location != undefined) {
                    window.location.href = location;
                }
            });
            
        });
    }