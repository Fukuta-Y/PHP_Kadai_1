トップ画面

https://user-info-search.onrender.com/MasterMente.php?mode=1

<pre>
テーブル作成は以下

PostgreSQL用 CREATE TABLE 文
各列のデータ型について
ID SERIAL PRIMARY KEY:

SERIAL はPostgreSQL特有のデータ型で、行が追加されるたびに自動的に1ずつ増える整数値を生成します。これにより、PHPコードで行っていた SELECT MAX(ID) + 1 のような手動でのID採番が不要になり、より堅牢になります。

PRIMARY KEY は、この列が一意でNULLを許さない主キーであることを示します。

NAME VARCHAR(255) NOT NULL:

VARCHAR(255) は可変長の文字列で、最大255文字まで格納できます。お名前なので、NULLは許可しないと想定しました (NOT NULL)。

SEX INTEGER:

PHPコードで数値（1や2）として扱われ、PDO::PARAM_INT でバインドされていたため INTEGER が適切です。NULLを許可するかは要件によりますが、ここではNULL可能としています。

POSTNO VARCHAR(8):

郵便番号はハイフンを含めて8文字（例: 123-4567）になることが多いので、VARCHAR(8) としました。もしハイフンを含まない7桁で扱うなら VARCHAR(7) でも良いでしょう。

ADDRESS1 VARCHAR(255):

住所の文字列なので VARCHAR を使用します。適切な最大長を設定してください。

ADDRESS2 VARCHAR(255):

住所の文字列なので VARCHAR を使用します。適切な最大長を設定してください。

BIKO TEXT:

「備考」なので、比較的長いテキストが格納される可能性があるため、TEXT 型が適しています。TEXT 型は長さに実質的な制限がありません。もし格納されるテキストが短いと分かっているなら VARCHAR(XXX) でも構いません。

CREATE TABLE T_USER_INFO (
    ID SERIAL PRIMARY KEY,
    NAME VARCHAR(255) NOT NULL,
    SEX INTEGER,
    POSTNO VARCHAR(8),
    ADDRESS1 VARCHAR(255),
    ADDRESS2 VARCHAR(255),
    BIKO TEXT
);
</pre>


デプロイ先 https://dashboard.render.com/web/srv-d1bvheje5dus73f16d7g

使用時は毎回「Clear build cache & deploy」を実行すること。

DB側も起動すること。
https://supabase.com/dashboard/project/szdcftaezxmhxjxqdeyi



