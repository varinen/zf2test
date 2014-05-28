<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

class User extends Form
{
    public function __construct($name = 'user')
    {
        parent::__construct($name);

        $this->setAttribute('method', 'post');

        $this->add(
           array(
               'name' => 'name',
               'type' => 'Zend\Form\Element\Text',
               'attributes' => array(
                   'placeholder' => 'Type name...',
                   'required' => 'required'
               ),
               'options' => array(
                   'label' => 'Name'
               )
           )
        );

        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'options' => array(
                    'label' => 'Email:'
                ),
                'attributes' => array(
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'Email Address...'
                )
            )
        );

        $this->add(
            array(
                'name' => 'phone',
                'options' => array(
                    'label' => 'Phone number'
                ),
                'attributes' => array(
                    'type' => 'tel',
                    'required' => 'required',
                    'pattern' => '^[\d-/]+$'
                )
            )
        );

        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'placeholder' => 'Password here...',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Verify Password'
                )
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\File',
                'name' => 'photo',
                'options' => array(
                    'label' => 'Your photo'
                ),
                'attributes' => array(
                    'required' => 'required',
                    'id' => 'photo'
                )
            )
        );

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'Zend\Form\Element\Csrf',
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'type' => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' => 'Submit',
                    'required' => 'false'
                )
            )
        );
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'email',
                        'filters' => array(
                            array(
                                'name' => 'StripTags',
                            ),
                            array(
                                'name' => 'StripTrim'
                            )
                        ),
                        'validators' => array(
                            array(
                                'name' => 'EmailAddress',
                                'options' => array(
                                    'messages' => array(
                                        'emailAddressInvalidFormat' => 'Email address format is invalid'
                                    )
                                )
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messages' => array(
                                        'isEmpty' => 'Email address is required'
                                    )
                                )
                            )
                        )
                    )
                )
            );


            $this->filter = $inputFilter;

        }
        return $this->filter;
    }
}
