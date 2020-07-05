<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Form;

use App\Entity\Certificate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CertificateType.
 */
class CertificateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName',
                TextType::class,
                [
                    'label' => 'Nom et prénom',
                    'attr' => [
                        'class' => 'form-control input-sm',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'mention',
                ChoiceType::class,
                [
                    'label' => 'Mention',
                    'choices' => array_flip(Certificate::CERTIFICATE_MENTION),
                    'attr' => [
                        'class' => 'form-control input-sm',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'challenge',
                TextType::class,
                [
                    'label' => 'Challenge',
                    'attr' => [
                        'class' => 'form-control input-sm',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'type',
                TextType::class,
                [
                    'label' => 'Type participation',
                    'attr' => [
                        'class' => 'form-control input-sm',
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'pseudo',
                TextType::class,
                [
                    'label' => 'Pseudo',
                    'attr' => [
                        'class' => 'form-control input-sm',
                    ],
                    'required' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Certificate::class]);
    }
}
