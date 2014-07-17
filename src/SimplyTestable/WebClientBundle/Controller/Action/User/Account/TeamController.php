<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\BaseController;

class TeamController extends BaseController {

    public function createAction() {
        if ($this->getRequestName() == '') {
            $this->get('session')->setFlash('team_create_error', 'blank-name');
            return $this->redirect($this->generateUrl('view_user_account_team_index_index', [], true));
        }

        $this->getTeamService()->setUser($this->getUser());
        $this->getTeamService()->create($this->getRequestName());

        return $this->redirect($this->generateUrl('view_user_account_team_index_index'));
    }


    /**
     * @return string
     */
    private function getRequestName() {
        return trim($this->getRequest()->request->get('name'));
    }


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TeamService
     */
    private function getTeamService() {
        return $this->get('simplytestable.services.teamservice');
    }

}