<?php

/*
 * This file is part of the YepsuaRADBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\RADBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Controller extends BaseController
{
    
    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @param string    $message  A message
     * @param \Exception $previous The previous exception
     *
     * @return NotFoundHttpException
     */
    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($this->translate($message), $previous);
    }
  
    /**
     * Creates a form to delete an entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id, array $options = array())
    {
        return $this->createFormBuilder(array('id' => $id), $options)
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * 
     * @return \Symfony\Component\Translation\Translator $translator 
     */
    protected function getTranslator(){
      return $this->get('translator');
    }
    
    /**
     * {@inheritdoc}
     *
     * @api
     */
    protected function translate($id, $parameters = array(), $domain = null , $locale = null){
      return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
    }
}
