# 2017 engineer internship in Tribal Media House

**Welcome To Tribal Media House !!!**

このリポジトリはTribal Media Houseにおけるエンジニア向けインターンシップ 2017 向けの課題リポジトリです．

## 概要

Tribal Media Houseでの開発においては，Web開発に関する知識と技術が最低限必要とされます．

この課題を通して，インターンの皆さんにはTribal Media HouseにおけるWeb開発で重要な以下のトピックについて学んでもらう事を目指します．

* HTTPプロトコルについて
* PHPについて
* Oauthプロトコルについて

また，周辺技術として以下のトピックについて触れて貰う事も目的とします．

* Dockerについて
* Webサーバ(Apache)について
* Rest API(Twitter Rest API)について
* GitHubについて
* Pull Requestについて

## 環境

この課題では，以下の環境での動作を想定しています．
万が一，動かない時はメンターエンジニアに質問するか，トラブルシュートするチャンスです :)

* macOS Sierra(10.12.3)
* Docker for Mac(Docker version 17.03.0-ce, build 60ccb22)
* Browser(Google Chrome 56.0.2924.87 (64-bit))
* Editor(お好きなモノでどうぞ)  

## 想定スキル

この課題を実施して貰うインターンの皆さんには，以下のスキルを保持している事と想定します．
必須ではありませんし，必要に応じてメンターエンジニアのサポートを受ける事が可能です．

* Twitterをユーザーとして使用した事がある
* `cd` `ls` などのシェルで使用する標準的なコマンドを使った事がある
* `git clone`などのgitで使用する標準的なgitコマンドを使った事がある  

## 事前準備

この課題を実施前に，以下の事前準備が必要となります．

1. Twitterアカウントの作成  
2. Twitterアプリケーションの作成  
3. このリポジトリのクローン  
4. Docker for Macのインストール  
5. イメージのビルド  
6. コンテナの起動  
  
### 1. Twitterアカウントの作成  
  
Tribal Media Houseにおいて，SNS(ソーシャル・ネットワーキング・サービス)の存在は重要な位置づけとなります．

また，各SNSが公開しているAPI（アプリケーションプログラミングインタフェース）を活用したサービス作りにおいては国内を牽引する事を目指しています．

今回の課題ではその中から Twitter を利用して，Web開発を学んで貰うことを目的とします．

Twitterアカウントは，インターン初日に発行される会社メールアドレスを使って新規作成するか，既にTwitterアカウントを持っている方はそちらを利用するかのどちらかを選択して下さい．
また，Twitterアカウントの作成方法については，この課題内で特に説明する必要がないと判断し，割愛します．

### 2. Twitterアプリケーションの作成

Twitterアカウントを作成したら`https://apps.twitter.com`にアクセスをします．

1. 「Create New App」ボタンをクリック
2. 必須項目を入力し，「Create your Twitter application」ボタンをクリック  
  a. 「Callback URL」は今回の課題では`http://localhost/index.php`とします．
3. 作成が成功し，アプリケーションが表示されるので内容を確認
4. 「keys and Access Tokens」タブを選択
5. Consumer Key (API Key) と Consumer Secret (API Secret) をメモ
6. 「Permissions」タブを選択
7. 権限を「Read Only」に設定

ここで設定した内容は後ほど編集可能です．
また，万が一 Consumer Secret (API Secret) が他人に知られてしまった場合，再発行を行う事が可能です．

### 3. このリポジトリのクローン

`git clone git@github.com:tribalmedia/internship_2017.git ~/internship_2017` を Macのターミナルで実行します．

`~/internship_2017`というフォルダが作成されればクローンは完了です．

念の為，`ls -al ~/internship_2017`を実行し，フォルダ内のファイルを確認して下さい．
このリポジトリと同様のファイル構成になっていれば確認も完了です．

### 4. Docker for Macのインストール

`https://www.docker.com/` からDocker for Macをダウンロードし，インストールを行います．

各種設定はデフォルト値とします．

### 5. イメージのビルド

Docker for Macのインストールには若干時間が掛かると思います．完了後，課題用のDocker Imageをビルドします．

1. ビルド対象のDockerfileが存在するフォルダまで移動  
`cd ~/internship_2017`  
2. ビルドコマンドを実行  
`docker build -t internship_2017 . # イメージ名をinternship_2017と指定`  
3. ビルドコマンドを眺める  
  
無事に完了すれば`Successfully built XXXXXXXXXXXX`と成功メッセージが表示されます．  
また，失敗した場合もコマンド結果の中には必ずヒントがあります．その為，みなさんにはビルドコマンドを眺めてみて欲しいのです．  
  
### 6. コンテナの起動  
  
ビルドしたDocker Imageを指定して，コンテナを起動します．  
  
1. このリポジトリのwwwフォルダが存在する階層まで移動  
`cd ~/internship_2017`  
2. コンテナ起動コマンドを実行  
`docker run -d -p 80:80 -v $PWD/www/:/var/www/html/ internship_2017`  
`# Macの80番ポートと起動するコンテナの80番ポートをポートフォワード`  
`# Macの$PWD/www/と起動するコンテナの/var/www/html/をバインド`  
3. 実行中コンテナの確認コマンドを実行  
`docker ps`  
  
コンテナの起動に成功すると，Dockerfile内で指定している`CMD ["/usr/sbin/httpd","-D","FOREGROUND"]`が実行され，apacheがバックグランド実行されます．  
その為，コンテナが常に実行中の状態になり，以下の様な内容が確認出来ます．  
  
```  
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                NAMES  
XXXXXXXXXXXX        internship_2017     "/usr/sbin/httpd -..."   x seconds ago       Up x seconds        0.0.0.0:80->80/tcp   xxxxx_xxxxx  
```  
  
## 課題  
  
やっと課題まで辿り着きました．事前準備が少し長かったでしょうか？この辺で少しコーヒー休憩でもしながら，メンターエンジニアとコミュニケーションをしてみて下さい．  
基本的にはどんな質問でもOKですが，先輩エンジニアに働き方を聞いたり，好きなテクノロジーの話で盛り上がったり出来ると最高ですね．  
  
さて，課題のアジェンダです．  
  
1. 稼働しているコンテナのWebサーバが稼働しているかを確認するには，どんな手段があるか．
2. このリポジトリのファイルから，API KeyとAPI Secretを記載すべきファイルを探して下さい．
3. `http://localhost`へWebアクセスをして，作成したTwitterアプリケーションを認証して下さい
4. ユーザーのアクセストークンを取得してその内容を確認してください
5. このリポジトリのファイルから，Tweetを取得するTwitterユーザー名を記載すべきファイルを探して下さい
6. 記載したTwitterユーザの最新のTweetを10件取得して下さい
  
ここまでは必須の課題となります．  

また，以下に発展の課題を用意しました．余裕がある人や興味がある人はチャレンジして下さい．  

* このリポジトリのPHPファイルを全体的に理解し，ファイル読み込みの順番やロジックをフロー図に表現してみる
* このリポジトリのソースコード内でTwitterとやり取りを行っている箇所はどの部分で，どんな手続を行っているか理解してみる
* このリポジトリの別ブランチを作成し，ソースコードのリファクタリングしてみる
* 修正したソースコードのPull RequestをGitHubで作成してみる
* その他のTwitter Rest APIを使用して，Tweet情報以外の情報を取得して描画してみる
* 自分専用のTwitterクライアントWebアプリケーションを作成してみる
  
などなど，可能性は無限大です！  
  
どんな事を発展の課題にするか，考えたり悩んだらメンターエンジニアに相談して見て下さい．  
みなさんのソースコードをレビューさせて貰ったり，みなさんとの技術のお話をさせて貰ったりして，なるべく適した発展の課題を提案できる様に頑張ってくれると思います :)
