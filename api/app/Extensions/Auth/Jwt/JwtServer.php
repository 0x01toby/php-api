<?php


namespace App\Extensions\Auth\Jwt;


use App\Extensions\Helper\Helpers;
use Laravel\Lumen\Application;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;


/**
 * jwt 服务
 * https://github.com/lcobucci/jwt/blob/3.3/README.md 使用文档
 * Class JwtServer
 * @package App\Extensions\Auth\Jwt
 */
class JwtServer
{
    /** @var Application $app */
    protected $app;
    protected $config;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $app['config'];
    }

    /**
     * 获取签名算法
     * @return Sha256
     */
    protected function getSigner()
    {
        switch (strtolower($this->config->get('jwt.signer'))) {
            case 'sha256':
                return new Sha256();
                break;
            default:
                return new Sha256();
        }
    }

    /**
     * 获取签名key
     * @return Key
     */
    protected function getKey()
    {
        return new Key($this->config->get('jwt.jwt_secret'));
    }

    /**
     * 获取jwt token
     * @param $uid string
     * @return string
     */
    public function getToken($uid)
    {
        $time = Helpers::getNowTime();
        return (string)(new Builder())
            // 设置发行人 （iss)
            ->issuedBy(Helpers::getCurrentUrl())// Configures the issuer (iss claim)
            // 设置接受者 (aud)
            ->permittedFor($this->config->get('app.url'))// Configures the audience (aud claim)
            // jwt id 唯一标识 （jti)
            ->identifiedBy(Helpers::getUuid(), true)// Configures the id (jti claim), replicating as a header item
            // iat 签发时间
            ->issuedAt($time)// Configures the time that the token was issue (iat claim)
            // nbf 多久之后，这个token可以使用
            ->canOnlyBeUsedAfter($time)// Configures the time that the token can be used (nbf claim)
            // exp 多久之后过期
            ->expiresAt($time + $this->config->get('jwt.expire'))// Configures the expiration time of the token (exp claim)
            // 存储uid
            ->withClaim('uid', $uid)// Configures a new claim, called "uid"
            // 生成token
            ->getToken($this->getSigner(), $this->getKey()); // Retrieves the generated token
    }

    /**
     * 验证token有效性 获取数据
     * @param $token
     * @return array|bool
     */
    public function validToken($token)
    {
        $parser = (new Parser())->parse($token);
        if (!$parser->verify($this->getSigner(), $this->getKey())) {
            return false;
        }

        if ($parser->isExpired()) {
            return false;
        }
        return $parser->getClaims();
    }

    /**
     * 获取jwt唯一的uuid
     * @return string
     */
    protected function genJti()
    {
        return Helpers::getUuid();
    }
}
