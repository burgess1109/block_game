# 敵人(障礙物)遊戲測驗
* 起因：
小弟面試時遇到了這個考題，也許這對大大們很基本，但小弟才疏學淺沒有遇過這類題目，只想分享一些小小心得，還請多指教

![圖示說明](https://github.com/burgess1109/block_game/blob/master/demo.jpg)

* 題目說明：

  該公司給了前端程式碼(詳 resources/views/game/game.blade.php)，執行會像上圖圖示，可輸入列數(number of rows)、欄數(number of columns)及敵人數(number of enemies)。
  
  從程式碼可看出，當這三個參數變動時，會發送AJAX request 給後端(請求路徑：/game/grid)，並帶有參數rowQuantity、columnQuantity、enemyQuantity及token。而後端收到參數後須回應隨機的敵人座標位置給前端(若敵人數設定四個(enemyQuantity)，就會出現四個隨機敵人座標)，前端收到後就會改變列欄數及敵人座標(紅底)

  遊戲規則很簡單：

  1.敵人座標位置(紅底)及其上下左右座標(灰底)被阻擋住，無法通行。
  
  2.無論如何必須要有一條從原點(綠底)到右下終點(黃底)道路，途中不能被擋住。

  如上圖DEMO的範例所示，我們仍可以找出一條以上從原點(綠底)到終點(黃底)的道路，表示敵人座標(紅底 (0,5);(2,1);(4,4);(6,2))是合乎規定的
  
  
* 發想：

  測驗一開始小弟我就串接前後端，並依據敵人數隨機取座標回傳至前端(當然隨機座標一定在列欄的範圍內)，看似一切順利，後來問題來了！當我隨機取了第一組敵人座標後，我怎麼知道後面隨機取的敵人座標有沒有擋住起點至終點的路？小弟不才，當時毫無頭緒，思索至測驗結束都還沒想出來好的驗證方式......
  
  
  後來有朋友指點，何不先隨機取一條道路，再去判斷敵人座標會不會影響此道路？頓時豁然開朗了，有時換一種思維方式真的結果就不同了！我一直陷入先取敵人座標再去驗證道路的泥潦。
  
  最後，想說這是個有趣的題目，就藉此來分享我的結果，下載後URL輸入 yourIP/game 即可進行，請多多指教
