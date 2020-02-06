A = {
    init: function () {
        this.NewMessage.init();

        _.$("#newMessage").click(function(e){
            if (e.target && e.target.id === 'newMessage') _.$("#newMessage").display("none");
        })
    }


    ,NewMessage:{
        init: function(){
          _.$("#newMessage_button").click(this.openNewMessage);
          _.$("#newMessage_form").submit(this.submitRequest);
            _.$(".mark_read").click(this.markAsRead);
            _.$(".reply").click(this.reply);

            _.$(".nav a.inbox").click(function(e){
                e.preventDefault();
                _.$(".nav a").removeClass("active")
                _.$(this).addClass("active");
                _.$("#tableInbox").display('table')
                _.$("#tableOutbox").display('none')

            });
            _.$(".nav a.outbox").click(function(e){
                e.preventDefault();
                _.$(".nav a").removeClass("active")
                _.$(this).addClass("active");
                _.$("#tableInbox").display('none')
                _.$("#tableOutbox").display("table");

            });
        }

        ,reply: function(e) {
            e.preventDefault();
            A.NewMessage.answerFrom = _.$(this).data('id');
            _.$("#newMessage").display("block");
        }

        ,markAsRead: function(e) {
            var id = _.$(this).data('id');
            _.postJSON("/profile/modules/markMessageRead.php",{
                message:id
            }, function(d){}, function(d){}, function(d){});
            _.$(this).parent().parent().toggleClass('message-unread');
        }

        ,openNewMessage: function(e) {
            e.preventDefault();
            delete A.NewMessage.answerFrom;
            _.$("#newMessage").display("block");
        }

        ,submitRequest: function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var request = new XMLHttpRequest();
            _.$("#newMessage_form p.status").HTML("<i class='info'>Sending message...</i>");
            request.open("POST", "/profile/modules/sendMessage.php");
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200)
                {
                    var res = JSON.parse(request.responseText);
                    if (res.status == 'OK') {
                        _.$("p.status").HTML("<i class='ok'>"+res.data+"</i>");
                    } else {
                        _.$("p.status").HTML("<i class='error'>"+ (res.message || 'An unexpected error occured!') +"</i>");
                    }
                }
            };

            return false;

            // this.submit();
            // _.$("#newMessage_form p.status").HTML("<i class='info'>Sending message...</i>");
            // var formData = new FormData(this);
            //
            // _.postJSON("/profile/modules/sendMessage.php",formData,function(d){
            //     _.$("p.status").HTML("<i class='ok'>"+d+"</i>");
            //     location.search = 'q='+new Date().getTime();
            // },function(e){
            //     _.$("p.status").HTML("<i class='error'>"+e+"</i>");
            // },function(r){
            //     _.$("p.status").HTML("<i class='error'>An unexpected error occured!</i>");
            //     console.error(r)
            // });
            // return false;
        }
    }

};

_.core(function() {
    A.init();
});