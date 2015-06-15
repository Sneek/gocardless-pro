<?php

namespace GoCardless\Pro\Models;

use GoCardless\Pro\Models\Abstracts\Entity;
use GoCardless\Pro\Models\Traits\Factory;

/**
 * Redirect flow entity
 *
 * @see Entity
 */
class RedirectFlow extends Entity
{
    use Factory;

    /**
     * Creditor entity
     *
     * @var Creditor
     */
    protected $creditor;

    /**
     * Mandate entity
     *
     * @var Mandate
     */
    protected $mandate;

    /**
     * The URI to redirect to upon success mandate setup.
     *
     * @var string
     */
    protected $success_redirect_url;

    /**
     * The URI to redirect the customer to to setup their mandate.
     *
     * @var string
     */
    protected $redirect_url;

    /**
     * Direct Debit scheme for the mandate. If specified, the payment
     * pages will only allow set-up of a mandate for the specified scheme.
     *
     * @var string
     */
    protected $scheme;

    /**
     * The customer's session ID
     *
     * @var string
     */
    protected $session_token;

    /**
     * Create Payment Flow with optional Creditor & Mandate
     *
     * @param Creditor $creditor Creditor
     * @param Mandate  $mandate  Mandate
     */
    public function __construct(Creditor $creditor = null, Mandate $mandate = null)
    {
        $this->setCreditor($creditor);
        $this->setMandate($mandate);
        $this->useBacs();
    }

    /**
     * Set credtiror
     *
     * @param Creditor $creditor Creditor entity
     *
     * @return $this
     */
    public function setCreditor(Creditor $creditor = null)
    {
        $this->creditor = $creditor;

        return $this;
    }

    /**
     * Set mandate
     *
     * @param Mandate $mandate Mandate entity
     *
     * @return $this
     */
    public function setMandate(Mandate $mandate = null)
    {
        $this->mandate = $mandate;

        return $this;
    }

    /**
     * Set to use the bacs scheme for the resulting mandate
     *
     * @return Mandate
     */
    public function useBacs()
    {
        return $this->setScheme('bacs');
    }

    /**
     * Set to use the sepa core scheme for the resulting mandate
     *
     * @return Mandate
     */
    public function useSepaCore()
    {
        return $this->setScheme('sepa_core');
    }

    /**
     * Is the scheme bacs?
     *
     * @return bool
     */
    public function isBacs()
    {
        return $this->getScheme() === 'bacs';
    }

    /**
     * Gets the Direct Debit scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Sets scheme internally. Use specific setter e.g. useBacs publically
     *
     * @param string $scheme Scheme type
     *
     * @return $this
     */
    protected function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Is the scheme SepeCore?
     *
     * @return bool
     */
    public function isSepaCore()
    {
        return $this->getScheme() === 'sepa_core';
    }

    /**
     * Gets the session token
     *
     * @return string
     */
    public function getSessionToken()
    {
        return $this->session_token;
    }

    /**
     * Gets the redirect URL
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * Gets the success redirect URL
     *
     * @return string
     */
    public function getSuccessRedirectUrl()
    {
        return $this->success_redirect_url;
    }

    /**
     * Set the success redirect URL
     *
     * @param string $successRedirectUrl Url to redirect user to after completion
     *
     * @return $this
     */
    public function setSuccessRedirectUrl($successRedirectUrl)
    {
        $this->success_redirect_url = $successRedirectUrl;

        return $this;
    }

    /**
     * Returns the entity as an array (as the API expects)
     *
     * @return array
     */
    public function toArray()
    {
        $redirectFlow = array_filter(get_object_vars($this));

        if ($this->creditor instanceof Creditor) {
            unset($redirectFlow['creditor']);
            $redirectFlow['links']['creditor'] = $this->creditor->getId();
        }

        if ($this->mandate instanceof Mandate) {
            unset($redirectFlow['mandate']);
            $redirectFlow['links']['mandate'] = $this->mandate->getId();
        }

        return $redirectFlow;
    }
}
