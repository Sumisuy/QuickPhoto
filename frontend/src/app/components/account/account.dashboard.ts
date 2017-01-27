import { Component } from '@angular/core';

import { ModifierService } from "../../services/modifier.service";

@Component({
    selector: 'account-details',
    moduleId: module.id,
    templateUrl: '../../resources/account/account.dashboard.html'
})

export class AccountDashboard {

    constructor(protected modifierService: ModifierService) {
        this.modifierService.ensureModifier();
    }
}
