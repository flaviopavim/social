function randomString(n) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < n; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}

var datetime;
function addZeroes(num) {
    if (num<10) {
        return '0'+num;
    }
    return num;
}
setInterval(function(){
    if (datetime!=null) {
        var sp=datetime.split(' ');
        var date=sp[0].split('-');
        var time=sp[1].split(':');
        var year=date[0];
        var month=date[1];
        var day=date[2];
        var hour=time[0];
        var minute=time[1];
        var second=time[2];


        var dt=new Date(year,month-1,day,hour,minute,second);
        //add 1 second
        dt.setSeconds(dt.getSeconds()+1);

        datetime=dt.getFullYear()+'-'+addZeroes(dt.getMonth()+1)+'-'+addZeroes(dt.getDate())+' '+
            addZeroes(dt.getHours())+':'+addZeroes(dt.getMinutes())+':'+addZeroes(dt.getSeconds());
            console.log('Datetime: '+datetime);
    }

},1000);

var data={
    'system':'base',
    'version':'1.1.0',
    'type':'app',
    'uuid':'abc123-def456-ghi789-jkl012-mno345',
    'session':randomString(32),
    'from':'javascript',
    'to':'php',
    'confirmed':{
        'chat':[1]
    },
    'data':{
        'action':[],
        'view':[],
        'chat':[],
        'feed':[],
    }
};

var refreshing=false;
var count=0;
var secs=7; //segundos do refresh
var countRefresh=0;

function refresh() {
    if (!refreshing) {
        refreshing=true;

        $.post("refresh/refresh.php",data,function(response){
           $('body').append(response);
           refreshing=false;
        }); 
    }
}
refresh();

setInterval(function(){
    count++;
    if (count%(secs*10)===0) {
        secs=7;
        refresh();
    }

    //pra destravar o refresh caso ele congelar nos requests
    if (count%(7*10)===0) {
        countRefresh++;
        if(countRefresh>3) {
            countRefresh=0;
            refreshing=false;
        }
    }

}, 100);



function view(pg) {
    data.data.view.push({
        'pg':pg
    });
}