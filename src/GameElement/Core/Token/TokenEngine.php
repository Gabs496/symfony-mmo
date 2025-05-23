<?php

namespace App\GameElement\Core\Token;

use App\GameElement\Core\Token\Exception\TokenExchangerNotFoundException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class TokenEngine
{
    protected array $registeredExchangers = [];
    public function __construct(
        #[AutowireIterator('token.exchanger')]
        /** @paramt iterable<TokenExchangerInterface> */
        protected iterable $tokenExchangers,
    ) {
    }

    public function exchange(TokenInterface $token): TokenizableInterface
    {
        $registeredExchanger = $this->registeredExchangers[$token->getExchangerClass()] ?? null;
        if ($registeredExchanger) {
            return $registeredExchanger->exchange($token);
        }

        foreach ($this->tokenExchangers as $exchanger) {
            $this->registeredExchangers[$exchanger::class] = $exchanger;
            if ($exchanger::class === $token->getExchangerClass()) {
                return $exchanger->exchange($token);
            }
        }

        throw new TokenExchangerNotFoundException("Token exchanger not found for class: " . $token->getExchangerClass() . " defined in " . $token::class);
    }
}