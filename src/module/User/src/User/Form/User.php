<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Class User
 * @package User\Form
 */
class User extends Form
{
    /**
     * Creates the form
     * @param string $name
     */
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

        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => 'Phone number'
            ),
            'attributes' => array(
                // Below: HTML5 way to specify that the input will be phone number
                'type' => 'tel',
                'required' => 'required',
                // Below: HTML5 way to specify the allowed characters
                'pattern'  => '^[\d-/]+$'
            ),
        ));

        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'placeholder' => 'Password here...',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Password'
                )
            )
        );

        $this->add(
            array(
                'name' => 'password_verify',
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

    /**
     * Defines input filters and validators for the form inputs
     *
     * @return null|InputFilter|InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->filter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            /**
             * Email input,
             * filters and valditators
             */
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'email',
                        'filters' => array(
                            array(
                                'name' => 'StripTags',
                            ),
                            array(
                                'name' => 'StringTrim'
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

            /**
             * Name input,
             * filters and valditators
             */
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'name',
                        'filters' => array(
                            array(
                                'name' => 'StripTags'
                            ),
                            array(
                                'name' => 'StringTrim'
                            )
                        ),
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messsages' => array(
                                        'isEmpty' => 'Name is required'
                                    )
                                )
                            )
                        )
                    )
                )
            );

            /**
             * Phone input,
             * filters and valditators
             */
            $inputFilter->add ( $factory->createInput ( array (
                'name' => 'phone',
                'filters' => array(
                    array ( 'name' => 'digits' ),
                    array ( 'name' => 'stringtrim' ),
                ),
                'validators' => array (
                    array (
                        'name' => 'regex',
                        'options' => array (
                            'pattern' => '/^[\d-\/]+$/',
                        )
                    ),
                )
            )));

            /**
             * Password input,
             * filters and valditators
             */
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'password',
                        'filters' => array(
                            array(
                                'name' => 'StripTags'
                            ),
                            array(
                                'name' => 'StringTrim'
                            )
                        ),
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'messsages' => array(
                                        'isEmpty' => 'Pasword is required'
                                    )
                                )
                            )
                        )
                    )
                )
            );

            /**
             * Password verification input,
             * filters and valditators
             */
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'password_verify',
                        'filters' => array(
                            array(
                                'name' => 'StripTags'
                            ),
                            array(
                                'name' => 'StringTrim'
                            )
                        ),
                        'validators' => array(
                            array(
                                'name' => 'identical',
                                'options' => array(
                                    'token' => 'password'
                                )
                            )
                        )
                    )
                )
            );

            /**
             * Image upload input,
             * filters and valditators
             */
            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name' => 'photo',
                        'validators' => array(
                            array(
                                'name' => 'filesize',
                                'options' => array(
                                    'max' => 2097152
                                )
                            ),
                            array(
                                'name' => 'filemimetype',
                                'options' => array(
                                    'mimeType' => 'image/png,image/x-png,image/jpg,image/jpeg,image/gif'
                                )
                            ),
                            array(
                                'name' => 'fileimagesize',
                                'options' => array(
                                    'maxWidth' => 200,
                                    'maxHeight' => 200
                                )
                            )
                        ),
                        'filters' => array(
                            array(
                                'name' => 'filerenameupload',
                                'options' => array(
                                    'target' => 'data/image/photos/',
                                    'randomize' => true,
                                )
                            )
                        )
                    )
                )
            );
/**
            $inputFilter->add ( $factory->createInput ( array (
                'name' => 'photo',
                'validators' => array (
                    array (
                        'name' => 'filesize',
                        'options' => array (
                            'max' => 2097152, // 2 MB
                        ),
                    ),
                    array (
                        'name' => 'filemimetype',
                        'options' => array (
                            'mimeType' => 'image/png,image/x-png,image/jpg,image/jpeg,image/gif',
                        )
                    ),
                    array (
                        'name' => 'fileimagesize',
                        'options' => array (
                            'maxWidth' => 200,
                            'maxHeight' => 200
                        )
                    ),
                ),
                'filters' => array (
                    // the filter below will save the uploaded file under
                    // <app-path>/data/images/photos/<tmp_name>_<random-data>
                    array (
                        'name'    => 'filerenameupload',
                        'options' => array (
                            // Notice: Make sure that the folder below is existing on your system
                            //         otherwise this filter will not pass and you will get strange
                            //         error message reporting that the required field is empty
                            'target'    => 'data/image/photos/',
                            'randomize' => true,
                        ),
                    ),
                ),
            ))); **/
            $this->filter = $inputFilter;
        }
        return $this->filter;
    }

    /**
     * Prevents assigning an input filter to the user form from the outside
     *
     * @param InputFilterInterface $inputFilter
     *
     * @return $this|void|\Zend\Form\FormInterface
     *
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('It is not allowed to set an input filter to this form');
    }
}
