<?php

/*
 * 配置文件
 * private_key_path和public_key_path或者private_key和public_key 二选一，必填，优先使用path
 */
$config = array(
    'app_id' => '2021002163628459',
    'notify_url' => '',
    'sign_type' => 'RSA2',
    'private_key' => 'MIIEowIBAAKCAQEArSs9M07qypSKOMNkLFrUfZGVEXjTsJgmVyh5bk8RN4y3WcLtyS4mimXOKenCh3ZGuJgNIwyS3V0D4L3Xd/09Id6SO3qOUShuI3+NOcZak98a7Tfbw77tX/R5YUH8bdRSJvc/EVAB0asiR5Z34Ue1QYM2PggMkBm5tQ5/hTgnvPYFSa5TUAs7E3wgS+TC1jTqEzPRtmdxEJsL4yUATQoRfDMOgb9hUpjmDi6Xdc2Fs0kU2wphjrB1LWQoN0rLy2BLgdD5LaK9/1Is8LUYSB/4CthmQcFOgA3rFQoH/XR2Hfej2k/kPu4V///uMAJ4bZ5a2vtDqFkQ1nvaJPXNHHrGpQIDAQABAoIBAQCe3mVmVgdn1UC99NxJKQd7L/2/qscjY2MBYTuObPGRsgJgUkpO4I0xOVcb8r6qAmO9ZJngxt9SQMyW+fPcvDLCiOxFrxkz8dChtpp9H7Hnqa6NZUq0Wcxy1Noq1RveMwUvhHOS0YDpt4Ragvv2bRAoS1WPMVk9Nqy+I8/wC2XBn7iD4x4Sat6iFIPoaQySjwLszxuPFDHpqJOzQQzGG3n684UJKsakqPdS0ucKqm2FlP7mEuVbKSkoZOtxUzob+SyXeGxbSZYoMgsAzot6mI7P43dAq1gTieKSSRcuirviGwn9ZIAznhU2C9meQz3BGFLLhCuj60sxTCbcvyxooKWBAoGBAPY/hUmUc0g7+Ii3Hy5KeeR/nfTbxIcOcyRhH8EiU1Tx8C/yxRb+5AYqPZNmkRcJBjvQxH0vAkenf2COL/Fj2DSLAIJcvK+dQJ0KRtq7uk5lGMKR1/rYEA864++4DjiD2mZJLSHSENi1xlVIGC/GP/lZnxZxOYqLQBZRo3aaxghFAoGBALQG1ibUCsS0X7HW23Pfh674LPqV37/gzoXslwZp/OROWyxE1U7UcmopyQ+vZTRCxwZY188nFEy53kZTH9ZQ6tAU83A6/Ytn2qYB2k5r5a8yndlTiCBrHAzkiPaOH3YjQQBWhm6UiYK1YF2vCkXGKjxyNgJSaEozek2CoLKe5JrhAoGAAgGmfmD9R3tlnQeQ83mb4KbBjLs1sLwHaCZ6ccJr12dZQ6rAvF07UmmJsufFCuEB8f7TewmlBRxyR0EhVuAvZM41JNrIls27NwxcY2/gJr9gIkW58stL5jPeo5dmVkOMxgSWn43soTdp9EvwFoORBeEbEL2/cEeOMCBeKps8PdkCgYBAwazKrr9o3lEF0XmDBsbxw0e6o6qmhKEFP4NbxUg4f48AL4pjHrxBP+KZB8hpshORTufiFfaRWtJ/jWsIVncAfmjK2A1X1fWqfUQfrWQjTxvEju2Ka7zdTl+OALWEc03wZy9YG4oTQFb3m/0f+BY9Q//1edsVxcqakwBnAAzPQQKBgCkj+Y55cMjpy/nO8ijojJJTJRWFlGfJvLEz9KiT5fKTZnuuMS6MCHwKSmsXBefWIya0i3O9sRRRrrYX9Tfh9wmt/Ee82pOdqLbyoWeznA1Q+GV0bBOhMmlrRjk/ahWi1QDrCPjJ5jPCY2VIucHeQJqdOHOX5NCiq0wTUs8MwzrL', //一行字符串，前后不要有空格和换行
    //支付公钥
    'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArSs9M07qypSKOMNkLFrUfZGVEXjTsJgmVyh5bk8RN4y3WcLtyS4mimXOKenCh3ZGuJgNIwyS3V0D4L3Xd/09Id6SO3qOUShuI3+NOcZak98a7Tfbw77tX/R5YUH8bdRSJvc/EVAB0asiR5Z34Ue1QYM2PggMkBm5tQ5/hTgnvPYFSa5TUAs7E3wgS+TC1jTqEzPRtmdxEJsL4yUATQoRfDMOgb9hUpjmDi6Xdc2Fs0kU2wphjrB1LWQoN0rLy2BLgdD5LaK9/1Is8LUYSB/4CthmQcFOgA3rFQoH/XR2Hfej2k/kPu4V///uMAJ4bZ5a2vtDqFkQ1nvaJPXNHHrGpQIDAQAB',
    //注意是支付宝公钥，用来解密支付宝返回数据签名，一行字符串，前后不要有空格和换行！！！
    'alipay_public_key'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAj4VFBvpxXreHnqT6Cy0hmX+czFFG+Bmx6Z1csyL3ZM3Cjn6Aa2lh/rfEPiR8N9d+uVmMwdSBQizv/Htj82RMITzMQGRev6daAYP9Xf22JfcKrAKBdTfeAeuPQJxa5rqHPAP2uzallCSZ5wXYMFwwLP9+oZBfKYZjU7stoxnw0S8dfKM920baRVVkCASPO3wCrblm++5A+dODqV2HYSQbv1WsWOOaONH+pCvk8lcoBE1Ue44IwN40ag9688gOCfUaIA0bt5/xqjk96LiYuoWvh9io5HvxT4NPk8N8i5AgC8duFgpYgnZNDGcuVPO+rNt1ENXXKWqJyHVoCS31fLlrywIDAQAB',
    'private_key_path' => '', //必须是证书所在的绝对路径
    'public_key_path' => '', //必须是证书所在的绝对路径
);
