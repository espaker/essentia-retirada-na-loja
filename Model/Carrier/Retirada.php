<?php
namespace Espaker\RetiradaNaLoja\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Psr\Log\LoggerInterface;

class Retirada extends AbstractCarrier implements CarrierInterface
{
    protected $_code = 'retiradaloja';

    protected $_rateResultFactory;
    protected $_rateMethodFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
    }

    public function collectRates(RateRequest $request)
    {
        $this->_logger->debug('RetiradaNaLoja chamado');

        if (!$this->getConfigFlag('active')) {
            $this->_logger->debug('RetiradaNaLoja inativo');
            return false;
        }

        $result = $this->_rateResultFactory->create();
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice(0);
        $method->setCost(0);

        $result->append($method);
        return $result;
    }


    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}
