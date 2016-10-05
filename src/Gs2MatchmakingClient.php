<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Matchmaking;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Matchmaking クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2MatchmakingClient extends AbstractGs2Client {

	public static $ENDPOINT = 'matchmaking';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param array $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * マッチメイキングリストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* matchmakingId => マッチメイキングID
	 * 		* ownerId => オーナーID
	 * 		* name => マッチメイキング名
	 * 		* description => 説明文
	 * 		* type => 種類
	 * 		* maxPlayer => 最大プレイヤー数
	 * 		* serviceClass => サービスクラス
	 * 		* callback => コールバックURL
	 * 		* createAt => 作成日時
	 * 		* updateAt => 更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeMatchmaking($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Matchmaking', 
					'DescribeMatchmaking', 
					Gs2MatchmakingClient::$ENDPOINT, 
					'/matchmaking',
					$query);
	}

	/**
	 * サービスクラスリストを取得
	 *
	 * @return array サービスクラス
	 */
	public function describeServiceClass() {
		$query = [];
		$result = $this->doGet(
				'Gs2Matchmaking',
				'DescribeServiceClass',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/serviceClass',
				$query);
		return $result['items'];
	}
	
	/**
	 * マッチメイキングを作成<br>
	 * <br>
	 * GS2-Matchmaking を利用するためにまず作成するデータモデルです。<br>
	 * マッチメイキングの設定項目として、マッチメイキングの方式や最大プレイヤー数を設定します。<br>
	 * 
	 * @param array $request
	 * * name => マッチメイキング名
	 * * description => 説明文
	 * * serviceClass => サービスクラス
	 * * type => 種類
	 * * maxPlayer => 最大プレイヤー数
	 * * callback => コールバックURL
	 * @return array
	 * * item
	 * 	* matchmakingId => マッチメイキングID
	 * 	* ownerId => オーナーID
	 * 	* name => マッチメイキング名
	 * 	* description => 説明文
	 * 	* type => 種類
	 * 	* maxPlayer => 最大プレイヤー数
	 * 	* serviceClass => サービスクラス
	 * 	* callback => コールバックURL
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function createMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('serviceClass', $request)) $body['serviceClass'] = $request['serviceClass'];
		if(array_key_exists('type', $request)) $body['type'] = $request['type'];
		if(array_key_exists('maxPlayer', $request)) $body['maxPlayer'] = $request['maxPlayer'];
		if(array_key_exists('callback', $request)) $body['callback'] = $request['callback'];
		$query = [];
		return $this->doPost(
					'Gs2Matchmaking', 
					'CreateMatchmaking', 
					Gs2MatchmakingClient::$ENDPOINT, 
					'/matchmaking',
					$body,
					$query);
	}

	/**
	 * マッチメイキングを取得
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * @return array
	 * * item
	 * 	* matchmakingId => マッチメイキングID
	 * 	* ownerId => オーナーID
	 * 	* name => マッチメイキング名
	 * 	* description => 説明文
	 * 	* type => 種類
	 * 	* maxPlayer => 最大プレイヤー数
	 * 	* serviceClass => サービスクラス
	 * 	* callback => コールバックURL
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 *
	 */
	public function getMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Matchmaking',
				'GetMatchmaking',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName'],
				$query);
	}

	/**
	 * マッチメイキングの状態を取得
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * @return array
	 * * status => 状態
	 *
	 */
	public function getMatchmakingStatus($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Matchmaking',
				'GetMatchmakingStatus',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. '/status',
				$query);
	}
	
	/**
	 * マッチメイキングを更新
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * description => 説明文
	 * * serviceClass => サービスクラス
	 * * callback => コールバックURL
	 * @return array
	 * * item
	 * 	* matchmakingId => マッチメイキングID
	 * 	* ownerId => オーナーID
	 * 	* name => マッチメイキング名
	 * 	* description => 説明文
	 * 	* type => 種類
	 * 	* maxPlayer => 最大プレイヤー数
	 * 	* serviceClass => サービスクラス
	 * 	* callback => コールバックURL
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function updateMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('serviceClass', $request)) $body['serviceClass'] = $request['serviceClass'];
		if(array_key_exists('callback', $request)) $body['callback'] = $request['callback'];
		$query = [];
		return $this->doPut(
				'Gs2Matchmaking',
				'UpdateMatchmaking',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName'],
				$body,
				$query);
	}
	
	/**
	 * マッチメイキングを削除
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 */
	public function deleteMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Matchmaking', 
					'DeleteMatchmaking', 
					Gs2MatchmakingClient::$ENDPOINT, 
					'/matchmaking/'. $request['matchmakingName'],
					$query);
	}

	/**
	 * Anybodyマッチメイキング - マッチメイキングを実行<br>
	 * <br>
	 * Anybodyマッチメイキングのマッチメイキングプロセスは、このAPIを呼び出すことで完結します。<br>
	 * このAPIを呼び出した段階で参加者を待っているギャザリングが存在すれば参加し、<br>
	 * 参加者を待っているギャザリングが存在しなければ、新しくギャザリングに作成して、そのギャザリングに参加します。<br>
	 * <br>
	 * 戻り値にはギャザリングに参加している人数が含まれますので、自分がギャザリングを作成したのかはそこで確認することができます。<br>
	 * <br>
	 * マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link anybodyDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link anybodyLeaveGathering()} を呼び出すことで中断できます。<br>
	 * GS2-Matchmaking にはホストという明確な役割は存在しないため、ギャザリングを作成したユーザがマッチメイキングを中断したとしてもマッチメイキングは継続されます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* updateAt => 更新日時
	 */
	public function anybodyDoMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'DoMatchmaking',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/anybody",
				$body,
				$query,
				$extparams);
	}

	/**
	 * Anybodyマッチメイキング - ギャザリングに参加しているユーザID一覧取得を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 * @return array
	 * * items => 参加ユーザID一覧
	 */
	public function anybodyDescribeJoinedUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Matchmaking',
				'DescribeJoinedUser',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/anybody/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * Anybodyマッチメイキング - ギャザリングからの離脱を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function anybodyLeaveGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		$this->doDelete(
				'Gs2Matchmaking',
				'LeaveGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/anybody/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * CustomAutoマッチメイキング - <br>
	 * <br>
	 * CustomAutoマッチメイキングを実行する場合は、基本的にはこのAPIを呼び出すことで完結します。<br>
	 * CustomAutoマッチメイキングのリクエストパラメータには、参加対象となるギャザリングの属性値の範囲を指定して行われます。<br>
	 * 属性値は最大5個指定することができ、属性値毎に検索する最小値・最大値を指定できます。<br>
	 * すべての属性値が希望する範囲内に収まっているギャザリングを見つけた場合はそのギャザリングに参加します。<br>
	 * <br>
	 * 一定時間内にすべてのギャザリングの検索を終えることができなかった場合は、searchContext というパラメータを応答します。<br>
	 * この場合、searchContext を指定して このAPIを再度呼び出すことで、検索を再開することができます。<br>
	 * この際に指定する検索条件は以前の searchContext と同じ条件にするようにしてください。<br>
	 * 条件が変更されたうえで、searchContext を利用した場合の動作は保証できません。<br>
	 * <br>
	 * すべてのギャザリングを検索した結果、対象となるギャザリングが存在しなかった場合は、新しくギャザリングを作成し、そのギャザリングに参加します。<br>
	 * <br>
	 * 戻り値にはギャザリングに参加している人数が含まれますので、自分がギャザリングを作成したのかはそこで確認することができます。<br>
	 * <br>
	 * マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link customAutoDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link customAutoLeaveGathering()} を呼び出すことで中断できます。<br>
	 * GS2-Matchmaking にはホストという明確な役割は存在しないため、ギャザリングを作成したユーザがマッチメイキングを中断したとしてもマッチメイキングは継続されます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * attribute1 => ギャザリング新規作成時の属性1
	 * * attribute2 => ギャザリング新規作成時の属性2
	 * * attribute3 => ギャザリング新規作成時の属性3
	 * * attribute4 => ギャザリング新規作成時の属性4
	 * * attribute5 => ギャザリング新規作成時の属性5
	 * * searchAttribute1Min => 検索対象ギャザリング属性1の下限
	 * * searchAttribute1Max => 検索対象ギャザリング属性1の上限
	 * * searchAttribute2Min => 検索対象ギャザリング属性2の下限
	 * * searchAttribute2Max => 検索対象ギャザリング属性2の上限
	 * * searchAttribute3Min => 検索対象ギャザリング属性3の下限
	 * * searchAttribute3Max => 検索対象ギャザリング属性3の上限
	 * * searchAttribute4Min => 検索対象ギャザリング属性4の下限
	 * * searchAttribute4Max => 検索対象ギャザリング属性4の上限
	 * * searchAttribute5Min => 検索対象ギャザリング属性5の下限
	 * * searchAttribute5Max => 検索対象ギャザリング属性5の上限
	 * * searchContext => 検索コンテキスト
	 * * accessToken => アクセストークン
	 * @return array
	 * * done => 検索が完了したか
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* updateAt => 更新日時
	 * * searchContext => 検索コンテキスト
	 */
	public function customAutoDoMatchmaking($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('attribute1', $request)) $body['attribute1'] = $request['attribute1'];
		if(array_key_exists('attribute2', $request)) $body['attribute2'] = $request['attribute2'];
		if(array_key_exists('attribute3', $request)) $body['attribute3'] = $request['attribute3'];
		if(array_key_exists('attribute4', $request)) $body['attribute4'] = $request['attribute4'];
		if(array_key_exists('attribute5', $request)) $body['attribute5'] = $request['attribute5'];
		if(array_key_exists('searchAttribute1Min', $request)) $body['searchAttribute1Min'] = $request['searchAttribute1Min'];
		if(array_key_exists('searchAttribute2Min', $request)) $body['searchAttribute2Min'] = $request['searchAttribute2Min'];
		if(array_key_exists('searchAttribute3Min', $request)) $body['searchAttribute3Min'] = $request['searchAttribute3Min'];
		if(array_key_exists('searchAttribute4Min', $request)) $body['searchAttribute4Min'] = $request['searchAttribute4Min'];
		if(array_key_exists('searchAttribute5Min', $request)) $body['searchAttribute5Min'] = $request['searchAttribute5Min'];
		if(array_key_exists('searchAttribute1Max', $request)) $body['searchAttribute1Max'] = $request['searchAttribute1Max'];
		if(array_key_exists('searchAttribute2Max', $request)) $body['searchAttribute2Max'] = $request['searchAttribute2Max'];
		if(array_key_exists('searchAttribute3Max', $request)) $body['searchAttribute3Max'] = $request['searchAttribute3Max'];
		if(array_key_exists('searchAttribute4Max', $request)) $body['searchAttribute4Max'] = $request['searchAttribute4Max'];
		if(array_key_exists('searchAttribute5Max', $request)) $body['searchAttribute5Max'] = $request['searchAttribute5Max'];
		if(array_key_exists('searchContext', $request)) $body['searchContext'] = $request['searchContext'];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'DoMatchmaking',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/customauto",
				$body,
				$query,
				$extparams);
	}

	/**
	 * CustomAutoマッチメイキング - ギャザリングに参加しているユーザID一覧取得を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 * @return array
	 * * items => 参加ユーザID一覧
	 */
	public function customAutoDescribeJoinedUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Matchmaking',
				'DescribeJoinedUser',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/customauto/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}
	
	/**
	 * CustomAutoマッチメイキング - ギャザリングからの離脱を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function customAutoLeaveGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doDelete(
				'Gs2Matchmaking',
				'LeaveGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/customauto/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングを作成<br>
	 * <br>
	 * Passcodeマッチメイキングの開始手段は2つに別れます。<br>
	 * ひとつ目は既存のギャザリングに参加すること。もう一つはこのAPIで実行できる、新しくギャザリングを作成する。という手段です。<br>
	 * <br>
	 * ギャザリングを新しく作成するにあたって必要なパラメータなどはありません。<br>
	 * このAPIを呼び出すことでギャザリングが新しく作られ、ギャザリングには固有のパスコード(8ケタ数字)が割り当てられます。<br>
	 * 割り当てられたパスコードは戻り値に含まれています。<br>
	 * <br>
	 * パスコードの上位は乱数、下位はミリ秒単位のタイムスタンプで構成されています。<br>
	 * そのため、非常に短い間隔でリクエストを出した時に、乱数もあるため可能性は低くいですがパスコードが衝突する可能性があります。<br>
	 * その場合はパスコードを入力した時に同一パスコードを持つギャザリングのうちどのギャザリングに参加するかは不定です。<br>
	 * <br>
	 * 万全を期するには、ミリ秒単位でルームの作成が多数衝突する頻度でギャザリングを作成する必要がある場合は、<br>
	 * Anybody や CustomAuto といった方法のマッチメイキングも併用していただき、友達同士と遊びたい場合にのみ Passcode 方式を利用するよう誘導いただくのが得策です。<br>
	 * <br>
	 * ギャザリング作成後は、マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link passcodeDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link passcodeLeaveGathering()} を呼び出すことで中断できます。<br>
	 * GS2-Matchmaking にはホストという明確な役割は存在しないため、ギャザリングを作成したユーザがマッチメイキングを中断したとしてもマッチメイキングは継続されます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* passcode => ギャザリング参加用パスコード
	 * 	* updateAt => 更新日時
	 */
	public function passcodeCreateGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'CreateGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode",
				$body,
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングに参加<br>
	 * <br>
	 * Passcodeマッチメイキングの開始手段は2つに別れます。<br>
	 * ひとつ目は新しくギャザリングを作成すること。もう一つはこのAPIで実行できる、既存のギャザリングに参加する。という手段です。<br>
	 * <br>
	 * パスコードの交換方法は GS2 では提供しません。<br>
	 * ソーシャル連携などの手段は各ゲームで実装頂く必要があります。<br>
	 * <br>
	 * 何らかの手段で得たパスコードを指定してこのAPIを呼び出すことで、既存のギャザリングに参加することができます。<br>
	 * <br>
	 * ギャザリング参加後は、マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link passcodeDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link passcodeLeaveGathering()} を呼び出すことで中断できます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * passcode => ギャザリング参加用パスコード
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* passcode => ギャザリング参加用パスコード
	 * 	* updateAt => 更新日時
	 */
	public function passcodeJoinGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('passcode', $request)) throw new NullPointerException();
		if(is_null($request['passcode'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'JoinGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode/join/". $request['passcode'],
				$body,
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングに参加しているユーザID一覧取得を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 * @return array
	 * * items => 参加ユーザID一覧
	 */
	public function passcodeDescribeJoinedUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Matchmaking',
				'DescribeJoinedUser',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングからの離脱を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function passcodeLeaveGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doDelete(
				'Gs2Matchmaking',
				'LeaveGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングの解散を実行。<br>
	 * <br>
	 * ギャザリングへのプレイヤー募集を中止し、解散します。<br>
	 * 解散によって完了コールバックが返ることはありません。<br>
	 * この操作はギャザリングの作成主のユーザのみ行うことができます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function passcodeBreakupGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doDelete(
				'Gs2Matchmaking',
				'BreakupGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode/". $request['gatheringId'],
				$query,
				$extparams);
	}

	/**
	 * Passcodeマッチメイキング - ギャザリングの早期終了を実行。<br>
	 * <br>
	 * ギャザリングへのプレイヤー募集を早期終了します。<br>
	 * Matchmaking で定義した規定人数に満ていない場合もマッチメイキング完了コールバックが返ります。<br>
	 * この操作はギャザリングの作成主のユーザのみ行うことができます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function passcodeEarlyCompleteGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'EarlyCompleteGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/passcode/". $request['gatheringId']. "/complete",
				$body,
				$query,
				$extparams);
	}
	
	/**
	 * Roomマッチメイキング - ギャザリングを作成<br>
	 * <br>
	 * Room 方式のマッチメイキングは以下のプロセスで成立します。<br>
	 * <ol>
	 * <li>{@link roomCreateGathering()} でギャザリングを作成</li>
	 * <li>{@link roomDescribeGathering()} でギャザリング一覧を取得</li>
	 * <li>気に入ったルームが見つかったら {@link roomJoinGathering()} でギャザリングに参加</li>
	 * </ol>
	 * このAPIでは1番目のプロセスのギャザリングの作成が行えます。<br>
	 * <br>
	 * ギャザリングの作成リクエストには、128バイト以下と非常に小さいですが、ギャザリングのメタ情報を付加することができます。<br>
	 * ここにはホストが遊びたいと思っているゲームモードなどの情報を付与し、ギャザリング一覧での表示に利用できます。<br>
	 * 129バイト以上のデータを利用したい場合はメタデータのURLを格納するなどして対処してください。<br>
	 * <br>
	 * ギャザリング作成後、マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link roomDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link roomLeaveGathering()} を呼び出すことで中断できます。<br>
	 * GS2-Matchmaking にはホストという明確な役割は存在しないため、ギャザリングを作成したユーザがマッチメイキングを中断したとしてもマッチメイキングは継続されます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * meta => メタデータ(Optional)
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* meta => メタデータ
	 * 	* updateAt => 更新日時
	 */
	public function roomCreateGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('meta', $request)) $body['meta'] = $request['meta'];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'CreateGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room",
				$body,
				$query,
				$extparams);
	}
	
	/**
	 * Roomマッチメイキング - ギャザリングに参加<br>
	 * <br>
	 * Room 方式のマッチメイキングは以下のプロセスで成立します。<br>
	 * <ol>
	 * <li>{@link roomCreateGathering()} でギャザリングを作成</li>
	 * <li>{@link roomDescribeGathering()} でギャザリング一覧を取得</li>
	 * <li>気に入ったルームが見つかったら {@link roomJoinGathering()} でギャザリングに参加</li>
	 * </ol>
	 * <br>
	 * このAPIでは3番目のプロセスのギャザリングへの参加が行えます。<br>
	 * ギャザリングの一覧取得からギャザリングへの参加がアトミックに行われるわけではないので、<br>
	 * このAPIを呼び出した段階では、ギャザリングが解散していたり、すでに満員になっている可能性があります。<br>
	 * そのような場合は、このAPIはエラー応答として、{@link BadRequestException} 例外をスローします。<br>
	 * <br>
	 * ギャザリング参加後、マッチメイキング完了コールバックをが返るまで待つことでマッチメイキングの完了を待つことができます。<br>
	 * マッチメイキングの進捗を確認したい場合は {@link roomDescribeJoinedUser()} を呼び出すことで、<br>
	 * ギャザリングに参加しているユーザIDが取得できるため、誰とマッチメイキングが行われているか途中経過を取得できます。<br>
	 * <br>
	 * ユーザ操作などにより、マッチメイキングを中断する場合は {@link roomLeaveGathering()} を呼び出すことで中断できます。<br>
	 * <br>
	 * ゲーム利用者にとって、最もニーズに合ったギャザリングに参加できるのが Room 方式のマッチメイキングの特徴ではありますが、<br>
	 * プレイヤー数が多いゲームにおいては、このアトミックに操作が行われないという点がUXにマイナスの影響をおよぼす可能性があります。<br>
	 * どうしても Room 方式でなければならない理由がないのであれば、他のマッチメイキング方式を採用することをおすすめします。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 * @return array
	 * * item
	 * 	* gatheringId => ギャザリングID
	 * 	* joinPlayer => 参加プレイヤー数
	 * 	* meta => メタデータ
	 * 	* updateAt => 更新日時
	 */
	public function roomJoinGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'JoinGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/". $request['gatheringId'],
				$body,
				$query,
				$extparams);
	}

	/**
	 * Roomマッチメイキング - ギャザリング一覧を取得<br>
	 * <br>
	 * Room 方式のマッチメイキングは以下のプロセスで成立します。<br>
	 * <ol>
	 * <li>{@link roomCreateGathering()} でギャザリングを作成</li>
	 * <li>{@link roomDescribeGathering()} でギャザリング一覧を取得</li>
	 * <li>気に入ったルームが見つかったら {@link roomJoinGathering()} でギャザリングに参加</li>
	 * </ol>
	 * <br>
	 * このAPIでは2番目のプロセスのギャザリング一覧の取得が行えます。<br>
	 * <br>
	 * ギャザリングの一覧をユーザに提供し、気に入ったギャザリングがあれば次のプロセスへ<br>
	 * 見つからなければ、先頭から取り直すか戻り値に含まれる nextPageToken を利用して次のページを案内できます。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * accessToken => アクセストークン
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* gatheringId => ギャザリングID
	 * 		* joinPlayer => 参加プレイヤー数
	 * 		* meta => メタデータ
	 * 		* updateAt => 更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function roomDescribeGathering($request, $pageToken = NULL, $limit = NULL) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Matchmaking',
				'DescribeGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/",
				$query,
				$extparams);
	}
	
	/**
	 * Roomマッチメイキング - ギャザリングに参加しているユーザID一覧取得を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 * @return array
	 * * items => 参加ユーザID一覧
	 */
	public function roomDescribeJoinedUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doGet(
				'Gs2Matchmaking',
				'DescribeJoinedUser',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}
	
	/**
	 * Roomマッチメイキング - ギャザリングからの離脱を実行<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 * 
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function roomLeaveGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doDelete(
				'Gs2Matchmaking',
				'LeaveGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/". $request['gatheringId']. "/player",
				$query,
				$extparams);
	}

	/**
	 * Roomマッチメイキング - ギャザリングの解散を実行。<br>
	 * <br>
	 * ギャザリングへのプレイヤー募集を中止し、解散します。<br>
	 * 解散によって完了コールバックが返ることはありません。<br>
	 * この操作はギャザリングの作成主のユーザのみ行うことができます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function roomBreakupGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doDelete(
				'Gs2Matchmaking',
				'BreakupGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/". $request['gatheringId'],
				$query,
				$extparams);
	}

	/**
	 * Roomマッチメイキング - ギャザリングの早期終了を実行。<br>
	 * <br>
	 * ギャザリングへのプレイヤー募集を早期終了します。<br>
	 * Matchmaking で定義した規定人数に満ていない場合もマッチメイキング完了コールバックが返ります。<br>
	 * この操作はギャザリングの作成主のユーザのみ行うことができます。<br>
	 * <br>
	 * accessToken には {@link http://static.docs.gs2.io/php/auth/class-GS2.Auth.Gs2AuthClient.html#_login GS2\Auth\Gs2AuthClient::login()} でログインして取得したアクセストークンを指定してください。<br>
	 *
	 * @param array $request
	 * * matchmakingName => マッチメイキング名
	 * * gatheringId => ギャザリングID
	 * * accessToken => アクセストークン
	 */
	public function roomEarlyCompleteGathering($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('matchmakingName', $request)) throw new NullPointerException();
		if(is_null($request['matchmakingName'])) throw new NullPointerException();
		if(!array_key_exists('gatheringId', $request)) throw new NullPointerException();
		if(is_null($request['gatheringId'])) throw new NullPointerException();
		if(!array_key_exists('accessToken', $request)) throw new NullPointerException();
		if(is_null($request['accessToken'])) throw new NullPointerException();
		$body = [];
		$query = [];
		$extparams = [
				'headers' => [
						'X-GS2-ACCESS-TOKEN' => $request['accessToken']
				]
		];
		return $this->doPost(
				'Gs2Matchmaking',
				'EarlyCompleteGathering',
				Gs2MatchmakingClient::$ENDPOINT,
				'/matchmaking/'. $request['matchmakingName']. "/room/". $request['gatheringId']. "/complete",
				$body,
				$query,
				$extparams);
	}
}