# 1. 微信授权

## 1.1 授权

GET /auth/jump[?redirect={encoded_uri}] HTTP/1.1

如果访问以下接口时遇到401，请先访问此地址进行微信授权登录。接受redirect参数。

**注意如果用户没有登录也会强制跳到这个接口进行微信授权登录**

## 1.2 测试接口

GET /auth/fake/{id} HTTP/1.1

**debug模式开启后，若知道自己user_id，可以直接使用此接口登录，绕过微信认证。**

# 2. 用户模块

## 2.1 个人信息更新

PUT /user HTTP/1.1

Content-Type: application/x-www-form-urlencoded

**下列全为可选项。**

```json
{
    "avatar_visible": integer,     	// 1：隐藏头像，0：不隐藏
    "phonesearch": integer,     	// 1：允许通过手机号查找，0：不允许
    "realnamesearch": integer,      	// 1：允许通过姓名查找，0：不允许
    "signature": string,     		//个性签名（可不填）
    "realname": string,
    "sex": integer(1男/0女),
    "phone": string,
    "signature": string,
    "name": string,
    "school": string,
    "upload_to_overt": integer(0/1)  // 1:可以上传到录音去, 0:不允许
}
```

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "修改失败"}
```

## 2.2 获取自己信息(用户个人页信息拉取)

GET /user HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: multipart/form-data

```json
{
    "id": integer,
    "name": string,
    "realname": string,
    "phone": string,
    "avatar": string(url),
    "sex": integer,
    "signature": string,
    "phonesearch": integer(0/1),
    "realnamesearch": integer(0/1),
    "school": string,
    "heals": {
        "myorders": {
            "name": string,
            "type": string("heal"/"story"),
            "created_at": timestamp,
            "avatar": string(url),
            "new": integer,
        },
        "mysongs": {
            "name": string,
            "type": string("heal"/"story"),
            "created_at": timestamp,
            "user": {
                "name": string,
                "avatar": string(url)
            }
        },
        "myoverts": {
            "name": string,
            "created_at": timestamp
        }
    }
}
```

失败时：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "获取头像失败"}
```

# 3. 治愈模块

## 3.1 创建经典治愈(“点歌”)

POST /heal  HTTP1.1

Content-Type: application/x-www-form-urlencoded

```json
{
    "name": string,     // 歌名
    "note": string,     // 备注
    "lang": string      // 语种
}
```

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.2 创建故事治愈(“点歌”)

POST /story  HTTP1.1

Content-Type: multipart/form-data

```json
{
    "content": string
}
```

| 编号 | 内容 |
| :-: | :-: |
| 1 | 图片1(.png/.jpg/.jpeg) |
| ... | ... |

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.3 歌曲和人名搜索(每项最多返回一百个)

GET /search  HTTP1.1

Content-Type: application/x-www-form-urlencoded

keyword={keyword}

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "heals": [
        {
            "id": integer,
            "name": string,
            "lang": string,
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url),
                "school": string,
                "phone": string,
                "signature": string,
                "sex": integer
            }
        },
        ...
    ],
    "overts": [
        {
            "id": integer,
            "name": string,
            "lang": string,
            "url": string(url),
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url),
                "school": string,
                "phone": string,
                "signature": string,
                "sex": integer
            }
        },
        ...
    ],
    "users": [
        {
            "id": integer,
            "name": string,
            "avatar": string(url),
            "signature": string
        },
        ...
    ]
}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.4 获取故事详情

GET /story/{id}  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "id": integer,
    "content": string,
    "laud": integer,
    "creator": {
        "id": integer,
        "name": string,
        "avatar": string(url),
        "phone": string
    },
    "pics": [string(url)...]
}

```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.5 复制到公共录音区(未定)

POST /overt  HTTP1.1

Content-Type: application/x-www-form-urlencoded

```json
{

}
```

## 3.6 治愈页首页信息获取

GET /homeinfo/{method} HTTP/1.1

其中method可取： "random"/"new"/"heat"

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "heals": [
        {
            "id": integer,     // id of heal
            "name": string,    // name of the song
            "created_at": timestamp,
            "creator":{
                "id": integer,   // id of user
                "name": string,
                "sex": integer(0/1),
                "signature": string,
                "phone": string,
                "avatar": string(url)
            }
        },
        ...
    ]
    "stories": [
        {
            "id": integer,     // id of story
            "created_at": timestamp,
            "content": string,
            "creator":{
                "id": integer,   // id of user
                "name": string,
                "sex": integer(0/1),
                "signature": string,
                "phone": string,
                "avatar": string(url)
            }
        },
        ...
    ]
    "overts": [
        {
            "id": integer,     // id of heal/story
            "name": string,    // name of the song. or it will be null if this item is for a story
            "created_at": timestamp,
            "lauds": integer,
            "lauded": integer(0/1),
            "creator":{
                "id": integer,   // id of user
                "name": string,
                "avatar": string(url)
            }
        },
        ...
    ]
    "overts"
}

```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.7 录音治愈

POST /record/heal/{id} HTTP/1.1

Content-Type: multipart/form-data

| 编号 | 内容(录音片段) |
| :-: | :-: |
| １ | 录音1(.mp3/.wmv/...) |
| ... | ... |

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.8 录音治愈(故事)

POST /record/story/{id} HTTP/1.1

Content-Type: multipart/form-data

```json
{
    "name": string
}
```

| 编号 | 内容(录音片段) |
| :-: | :-: |
| １ | 录音1(.mp3/.wmv/...) |
| ... | ... |

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 3.9 录音点赞(取消点赞)

PUT  /laud/{type}/{id}  HTTP1.1

**其中type可取："story"/"overt"/"seasnail"**

id则是该录音在相应模块的id

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "点赞失败"}`
`{"message" : "取消点赞失败"}`

# 4. 海螺模块

## 4.1 投掷海螺

POST /seasnail  HTTP1.1

Content-Type: multipart/form-data

```json
{
    "name": string,
    "maxrelays": integer
}
```

| 编号 | 内容(录音片段) |
| :-: | :-: |
| １ | 录音1(.mp3/.wmv/...) |
| ... | ... |

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`

## 4.2 获取海螺详情

GET /seasnail/{id}  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "id": integer,
    "name": string,
    "maxrelays": integer   // 最大接唱次数
    "restrelays": integer,     // 用户本日剩余接唱次数
    "relays": [
        {
            "id": integer,
            "laud": integer,
            "lauded": integer(0/1),
            "recording": string(url),
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url)
            }
        },
        ...
    ]
}
```

失败时(例子)：

HTTP/1.1 404 Not Found

Content-Type: application/json

`{"message" : "海螺不存在！"}`


## 4.3 海滩列表

GET /list/seasnails HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
[
    {
        "id": integer, // 海螺的id
        "name": string,
        "updated_at": timestamp,
        "creator": {
            "id": integer,
            "name": string,
            "avatar": string(url)
        }
    },
    ...
]
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxxxxxx"}`


## 4.4 随机捕捞

GET /randomseasnail  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "id": integer,
    "name": string,
    "maxrelays": integer   // 最大接唱次数
    "restrelays": integer,     // 用户本日剩余接唱次数
    "relays": [
        {
            "id": integer,
            "laud": integer,
            "lauded": integer(0/1),
            "recording": string(url),
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url)
            }
        },
        ...
    ]
}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "该功能尚未开放！"}`


## 4.5 海螺展馆

GET /list/fullseasnails  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
[
    {
        "id": integer,    // 海螺id
        "name": string,
        "creator": {
            "id": integer,
            "name": string,
            "avatar": string(url)
        }
    },
    ...
]
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxxx"}`

## 4.6 我的海螺

GET /list/my/seasnails  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
    "created": [
        {
            "id": integer,   // 海螺id
            "name": string,  // 歌名
            "updated_at": timestamp,
            "maxrelays": integer, // 最大接唱次数
            "relayed": integer,   // 已经被接唱次数
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url)
            }
        },
        ...
    ],
    "joined": [
        {
            "id": integer,   // 海螺id
            "name": string,  // 歌名
            "updated_at": timestamp,
            "creator": {
                "id": integer,
                "name": string,
                "avatar": string(url)
            }
        },
        ...
    ]
}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxxx"}`

## 4.7 海螺接唱请求接口

**用户在点下接唱按钮时，先请求这个接口，等到返回200了再开始录音！！！防止冲突用的！！**

POST /seasnail/{id}  HTTP1.1

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "该海螺正在被接唱或已达最大接唱次数！"}`

## 4.8 海螺接唱接口

POST /seasnail/{id}  HTTP1.1

Content-Type: multipart/form-data

| 编号 | 内容(录音片段) |
| :-: | :-: |
| １ | 录音1(.mp3/.wmv/...) |
| ... | ... |

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "xxxxx"}`


# 6. 聊天室

## 6.1 聊天室首页

GET  /chatting  HTTP1.1

成功时：

HTTP/1.1 200 OK

```js
[
    {
        "target"：{    						//目标对象的个人信息, 暂定——如为晚安推送，此处为null
            "id": integer,
            "name": string,
			"avatar": text(url)
        }, 
        "last":{    						//最后一条的信息
            "id":integer,  					//最后一条信息的id
            "user1":integer,  				//两个用户之一
            "user2":integer,  				//两个用户之二
            "from":integer,  				//最后一条信息来自哪个用户
            "content":text,					//最后一条信息的内容
            "time":datatime,				//最后一条信息的时间
            "type":integer,  				//消息类型 1:普通消息 2:录音 3:系统消息
        },
        "number":5   						//未读消息数量
    },
    {
        ……
    }
]
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "加载失败"}`

## 6.2 聊天室内部信息读取

GET  /chatting/cell  HTTP1.1

Content-Type: application/x-www-form-urlencoded

```json
{
    //按6.1中的顺序，不能互换
    "user1":integer,  				//两个用户之一
    "user2":integer,  				//两个用户之二
    "last_id": integer				//最后一条的信息的id, 信息加载将从该id往后加载（不包括该id）
    								//默认为0，视为加载全部
}
```

成功时：

HTTP/1.1 200 OK

```js
[
    {
        "id":integer,  					//信息的id
		"from":integer,  				//发送该信息的用户id
        "user":{    					//发送该信息的用户的信息
            "id": integer,
            "name": string,				//用户名字
			"avatar": text(url) 		//用户头像
        }, 
        "content":text,					//信息的内容
        "time":datatime,				//信息的时间
        "type":integer,  				//消息类型 1:普通消息 2:录音 3:系统消息
    },
    {
        ……
    }
]
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "加载失败"}`

## 6.3 发送消息

POST  /chatting/send  HTTP1.1

Content-Type: application/json

```js
{
    //按6.1中的顺序，不能互换
    "user1":integer,  				//两个用户之一
    "user2":integer,  				//两个用户之二
    "from":integer,  				//发送该信息的用户id
    "content":text,					//信息的内容
}
```

成功时：

HTTP/1.1 200 OK

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

`{"message" : "发送失败"}`

# 7. 排行榜

> 获取头像见2.2

## 7.1 华工榜

GET  /rank/scut  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
	"myRank":"我的排名",
	"user_id":["第一名id","第二名id","第三名id",...]
}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "获取排名失败"}
```

## 7.2 总榜

GET  /rank/all  HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{
	"myRank":"我的排名",
	"user_id":["第一名id","第二名id","第三名id",...]
}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "获取排名失败"}
```

# 8. 举报机制

## 8.1 意见反馈

POST  /feedback/suggestion  HTTP1.1

Content-Type: application/json

```json
{
    "phone_type":string,  			//手机型号
    "problem":text,  				//反馈的bug
    "pic_url":string,  				//bug图片
}
```

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{"message" : "反馈成功"}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "反馈失败"}
```

## 8.2 海螺举报

POST  /feedback/report  HTTP1.1

Content-Type: application/json

```json
{
    "seasnail_id":integer,			//举报的海螺
	"reason":text					//举报原因
}
```

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{"message" : "举报成功"}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "举报失败"}
```

# 9. 加积分

POST /points HTTP1.1

Content-Type: application/json

```js
{
    "task":integer,  		//1：签到，2：首次点歌，3：接唱海螺
}
```

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{"message" : "加分成功"}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "加分失败"}
```

# 10. 抽奖区

## 10.1 抽奖

PATCH /prize/try HTTP1.1

成功时：

HTTP/1.1 200 OK

Content-Type: application/json

```json
{"prize_name" : "奖品名称"}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "积分不足"}
```


## 10.2 我的奖品

GET /prize/my HTTP1.1

成功时：

HTTP/1.1 200 OK


Content-Type: application/json

```json
{"prize_name":["奖品一","奖品二","奖品三",...]}
```

失败时(例子)：

HTTP/1.1 403 Forbidden

Content-Type: application/json

```json
{"message" : "查询失败"}
```
