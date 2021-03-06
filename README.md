# sgphp

sgphpとは、新月のPHP実装です。sakuは実装が不必要に複雑で、いじるのが面倒くさかったのでPHPでつくり直す事にした。
まともな実装がsaku以外にないのもよくない。
プロトコルドキュメントが本当に最低限で、アプリケーションの実装方法にまで踏み込んでいないので、そのあたりも改善できれば。

## 特徴

- レンタルサーバーで動く
- プロセス常駐しない

## インストール方法

ソース一式をルートディレクトリに配置。
dataディレクトリを777に。
server.phpにアクセスすると、data/nodelist.txtが作られる。必要なら手でサーバを追加。
client.phpにある、crawl()を動かすと、他ノードから最新5件のデータを持ってこれる。
client.phpを適当にいじってjoinしないと他ノードがアクセスしてくれないよ。

## TODO

まだできていないのでこれからやっていく事

- スレッド表示系
- 投稿機能
- 署名関連
- タグ関連
- gzipサポート
- Server::join()でのホスト名省略時gethostbyaddr()

## Memo

- 置くだけで使えるようにしたい。プロセス常駐させたくない。cronはなるべく使いたくない。レンタルサーバでも動くように。
- でかいファイルの読み込みはmemory_limitで落としてるけど、fopen() & fgets() である程度はカバーできるかもしれない。
- 全部GETでやるのは大変おもしろい。file_get_contents()が大活躍。
- /joinの後、他ノードからよくやってくる通知は、/have,/update。
- /recent/時刻 の時刻は、いつにするべきか。前回recent時の時間を覚えておく?
- sakuの場合、投稿されると、gateway.pyのrec.build()で投稿をつくり、UpdateQueueを動かすようだ。
- 画像が添付されてるクソ重いレコードをどう扱うか。/haveとか/headで容量を取得できないものか。添付ファイルが存在するレコードは無視するという方法がかなり良さそう。
- ファイルは、無限個のレコードを格納できるので、システム側できるようにする。

### mysqlを使うとした場合のTable構成

プロトコルに書かれている「ファイル」と「レコード」の話をみていると、RDBMSで運用するべきに見える。mysqlで運用する場合のTable構成を考える。

- file
 - id(スレタイのmd5)
 - timestamp
 - title

- record
 - id(bodyのmd5)
 - timestamp
 - body

