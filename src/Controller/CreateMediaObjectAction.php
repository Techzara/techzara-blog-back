<?php
/**
 * @Author <julienrajerison5@gmail.com>
 *
 * This file is part of techzara blog
 */

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class CreateMediaObjectAction.
 */
class CreateMediaObjectAction
{
    /**
     * @param Request $request
     *
     * @return MediaObject
     */
    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;

        return $mediaObject;
    }
}
