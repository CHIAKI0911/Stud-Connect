//同じ日付で2回目以降ならローディング画面非表示の設定

var splash_text = $.cookie('accessdate'); //キーが入っていれば年月日を取得
var myD = new Date();//日付データを取得
var myYear = String(myD.getFullYear());//年
var myMonth = String(myD.getMonth() + 1);//月
var myDate = String(myD.getDate());//日
    
// if (splash_text != myYear + myMonth + myDate) {//cookieデータとアクセスした日付を比較↓
        $("#AM").css("display", "block");//１回目はローディングを表示
        setTimeout(function () {
            $("#AM").delay(3000).fadeOut('slow');
    //ローディング画面を1.5秒（1500ms）待機してからフェードアウト

    $("#AM_logo").delay(5000).fadeOut('slow');
    //ロゴを1.2秒（1200ms）待機してからフェードアウト
        setTimeout(function () {
            $("#AM").fadeOut(1000, function () {//1000ミリ秒（1秒）かけて画面がフェードアウト
            var myD = new Date();
            var myYear = String(myD.getFullYear());
            var myMonth = String(myD.getMonth() + 1);
            var myDate = String(myD.getDate());
            $.cookie('accessdate', myYear + myMonth + myDate); //accessdateキーで年月日を記録
        });
        }, 1700);//1700ミリ秒（1.7秒）後に処理を実行
    });
// }else {
    // $("#AM").css("display", "none");//同日2回目のアクセスでローディング画面非表示
// }  