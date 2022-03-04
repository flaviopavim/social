var localID=0;
var roll=true;
var toID=0;
var roomID=0;
var key=new Array();

function decryptChat() {

    

    $('.crypted').each(function(){
        var class_=$(this).attr('class');
        if(class_!='crypted'){
            var md5_=class_.split(' ')[1];
            if (md5_===md5(key[toID])) {
                var msg=$(this).html();
                $(this).parent('div').children('.msg-body').html(decrypt(msg,key[toID]));
            } else {
                $('.msg-'+md5_).html('<i class="glyphicon glyphicon-lock"></i>');
            }
        } else {
            var msg=$(this).html();
            $(this).parent('div').children('.msg-body').html(msg);
        }
    });
    if (roll) {
        $('#center').scrollTop($('#center')[0].scrollHeight);
    }
}

function mode(m){
    if (m=="feed") {
        $('#center-top, #center-bottom').hide();
        $('#feed').show();
        $('#chat-container').hide();
        $('#center').css({height: 'calc(100% - 50px)'});
    } else if (m=="chat") {
        $('#center-top, #center-bottom').show();
        $('#feed').hide();
        $('#chat-container').show();
        $('#center').css({height: 'calc(100% - 150px)'});
    }
}

function chat(to_id) {
    toID=to_id;
    key[to_id]='';
    mode('chat');
    data.data.action.push({
        'action':'chat',
        'to_id':to_id
    });
    secs=1;
    refresh(true);
}



$(function(){

    chat(1);

    //identificar se a rolagem do chat distanciou do final
    $('#center').scroll(function(){
        if($(this).scrollTop()+$(this).innerHeight()>=$(this)[0].scrollHeight){
            roll=true;
        } else {
            roll=false;
        }
    });

    $('#chat-top .glyphicon-remove').click(function(){
        $('#chat').animate({
            'width':'0px',
            'height':'0px',
            'opacity':'0'
        },500);
    });


    $('#msg').keyup(function(e){
        if (e.keyCode===13) {
            var msg=$('#msg').val();
            if (msg!=='') {

                //verificar se o primeiro caracter é /
                if (msg.charAt(0)==='/') {
                    //remover o /
                    var cmd=msg.substring(1).split(' ')[0];
                    //se o comando for /k
                    //comando pra mudar chave (key), da criptografia
                    if (cmd==='nick') {
                        var nick=msg.substring(1).split(' ')[1];

                        data.data.action.push({
                            'action':'nick',
                            'nick':nick
                        });

                    } else if (cmd==='k' || cmd==='key' || cmd==='md5') {
                        var param=msg.substring(1).split(' ')[1];
                        if (msg==='/k' || msg==='/k ') {
                            if (confirm('Tem certeza que deseja remover a chave de criptografia?')) {
                                key[toID]='';
                                decryptChat();
                            }
                        } else {
                            //if (confirm('Tem certeza que deseja alterar a chave de criptografia?')) {
                            key[toID]=param;
                            decryptChat();
                            //}
                        }
                    } else if (cmd==='s' || cmd==='signup') {

                        var login=msg.substring(1).split(' ')[1];
                        var pass=msg.substring(1).split(' ')[2];
                        var pass2=msg.substring(1).split(' ')[3];
                        if (pass===pass2) {

                            data.data.action.push({
                                'action':'signup',
                                'login':login,
                                'pass':pass
                            });

                            loading(true);
                        } else {
                            alert('As senhas não conferem!');
                        }
                    } else if (cmd==='p' || cmd==='pass') {

                        //recuperação de senha se tiver email cadastrado


                    } else if (cmd==='l' || cmd==='login') {

                        var login=msg.substring(1).split(' ')[1];
                        var pass=msg.substring(1).split(' ')[2];

                        data.data.action.push({
                            'action':'login',
                            'login':login,
                            'pass':pass
                        });

                        loading(true);
                    } else if (cmd==='logout' || cmd==='exit') {
                        data.data.action.push({'action':cmd});
                        loading(true);
                    } else if (cmd==='u' || cmd==='user') {
                        alert('Comando ainda não implementado')
                    } else if (cmd==='r' || cmd==='room') {
                        alert('Comando ainda não implementado')
                    } else if (cmd==='truncate' || cmd==='reset') {
                        data.data.action.push({'action':cmd});
                        loading(true);
                    } else {
                        alert('Comando não encontrado')
                    }
                    
                } else {

                    localID++;

                    var time=datetime.split(' ')[1].split(':');
                    var hour=time[0];
                    var minute=time[1];
                    var datetime_=hour+':'+minute;

                    data.data.chat.push({
                        'local_id':localID,
                        'to_id':toID,
                        'md5':(key[toID]!==''?md5(key[toID]):''),
                        'msg':(key[toID]!==''?crypt(msg,key[toID]):msg),
                        'datetime':datetime_
                    });

                    //add to chat
                    $('#chat-container').append(
                        '<div id="msg-'+localID+'" class="msg baloon-right msg-temp">'+
                        '   <div class="msg-header"><div class="msg-title">Você</div></div>'+
                        '   <div class="crypted'+(key[toID]?' '+md5(key[toID]):'')+'">'+(key[toID]!==''?crypt(msg,key[toID]):msg)+'</div>'+
                        '   <div class="msg-body'+(key[toID]?' msg-'+md5(key[toID]):'')+'"></div>'+
                        '   <div class="msg-footer">'+datetime_+'</div>'+
                        '</div>');
                    if (roll) {
                        $('#center').scrollTop($('#center')[0].scrollHeight);
                    }

                    decryptChat();

                }
                secs=1;
                $('#msg').val('');
            }
        }
    });
});


