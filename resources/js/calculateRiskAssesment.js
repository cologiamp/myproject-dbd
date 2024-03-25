import {ref} from "vue";
import Swal from "sweetalert2";
import {nextTab} from "@/nextAction.js";

const CAPACITY_LOW = 0;
const CAPACITY_MEDIUM = 1;
const CAPACITY_HIGH = 2;

const KNE_NONE = 0;
const KNE_SOME = 1;
const KNE_GOOD = 2;

const RISK_PROFILE_NOT_SUITABLE = 0;
const RISK_PROFILE_CAUTIOUS = 1;
const RISK_PROFILE_BALANCED = 2;
const RISK_PROFILE_ADVENTUROUS = 3;

export let forceRefresh = ref(1);
export let tabSections = ref(0);

function inRange(x, min, max) {
    return x >= min && x <= max;
}

function saveSectionScore(id, score, type, section, sidebarItemsLength) {
    axios.put(`/api/risk-outcome/${ id }/assess-outcome`, {
        score: score,
        type: type,
        section: section
    }).then((response) => {
        Swal.fire({
            title: 'Done',
            text: 'Assessment submitted',
            icon: 'success',
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: "OK",
        }).then((result) => {
            if (result.isConfirmed) {
                nextTab(sidebarItemsLength);
            }
        });
    }).catch((error) => {
        Swal.fire({
            title: 'Error',
            text: error,
            icon: 'error'
        });
    });
}

export function calculateCapacityForLoss(total, type, id, sidebarItemsLength) {
    let riskType = type === 0 ? 'investment' : 'pension';
    let capacityScore = CAPACITY_LOW

    if (inRange(total, 3, 11))
    {
        capacityScore = CAPACITY_LOW;
    } else if (inRange(total, 12, 20))
    {
        capacityScore = CAPACITY_MEDIUM;
    } else if (inRange(total, 21, 30))
    {
        capacityScore = CAPACITY_HIGH;
    }

    saveSectionScore(id, capacityScore, riskType, 'capacity_for_loss', sidebarItemsLength);
}

export function calculateKnE(equities, type, id, sidebarItemsLength) {
    let riskType = type === 0 ? 'investment' : 'pension';
    let sum = 0;

    Object.entries(equities).forEach(equity => {
        const [key, item] = equity;
        sum += item.value
    });

    let score = Math.round(sum / equities.length);    let kneScore = KNE_NONE;

    if (score === 2) {
        kneScore = KNE_SOME;
    } else if (score === 3) {
        kneScore = KNE_GOOD;
    }

    saveSectionScore(id, kneScore, riskType, 'knowledge_and_experience', sidebarItemsLength);
}

export function calculateRiskProfile(stepForm, id, sidebarItemsLength) {
    let riskProfileScore = getRiskProfileScore(stepForm);

    if (riskProfileScore === undefined) {
        Swal.fire({
            title: "Assessment conflict",
            text: "A conflict has been detected during assessment please try again.",
            icon: "warning"
        });
    } else {
        saveSectionScore(id, riskProfileScore, 'risk_profile', 'risk_profile', sidebarItemsLength);
    }
}

function getRiskProfileScore(stepForm) {
    let cautious = 0;
    let balanced = 0;
    let adventurous = 0;

    // Q2 - stepForm.day_to_day_volatility
    if (stepForm.day_to_day_volatility === 0) {
        cautious += 1;
    } else if (stepForm.day_to_day_volatility === 1) {
        balanced += 1;
    } else if (stepForm.day_to_day_volatility === 2) {
        adventurous += 1;
    }

    // Q3 - stepForm.short_term_volatility
    let short_term_volatility = getShortTermVolatilitySum(stepForm.short_term_volatility);
    if (short_term_volatility === 30) {
        cautious += 1;
    } else if (inRange(short_term_volatility, 20, 29)) {
        balanced += 1;
    } else if (inRange(short_term_volatility, 6, 19)) {
        adventurous += 1;
    }

    // Q4 - stepForm.medium_term_volatility
    if (stepForm.medium_term_volatility === 0) {
        cautious += 1;
    } else if (stepForm.medium_term_volatility === 1) {
        balanced += 1;
    } else if (stepForm.medium_term_volatility === 2) {
        adventurous += 1;
    }

    // Q5 - stepForm.volatility_behaviour
    if (stepForm.volatility_behaviour === 0) {
        cautious += 1;
    } else if (stepForm.volatility_behaviour === 1) {
        balanced += 1;
    } else if (stepForm.volatility_behaviour === 2) {
        adventurous += 1;
    }

    // Q6 - stepForm.long_term_volatility
    if (stepForm.long_term_volatility === 0) {
        cautious += 1;
    } else if (stepForm.long_term_volatility === 1) {
        balanced += 1;
    } else if (stepForm.long_term_volatility === 2) {
        adventurous += 1;
    }

    // Q7 - stepForm.time_in_market
    if (stepForm.time_in_market === 0) {
        cautious += 1;
    } else if (stepForm.time_in_market === 1 || stepForm.time_in_market === 2) {
        balanced += 1;
    } else if (stepForm.time_in_market === 3) {
        adventurous += 1;
    }

    // Q1 - stepForm.comfort_fluctuate_market
    console.log('cautious= ' + cautious + 'balanced= ' + balanced + 'adventurous= ' + adventurous )
    if (stepForm.comfort_fluctuate_market === false) {
        return RISK_PROFILE_NOT_SUITABLE;
    }
    else if (cautious >= 4) {
        return RISK_PROFILE_CAUTIOUS;
    }
    else if (cautious === 3 && balanced === 3) {
        return RISK_PROFILE_BALANCED;
    }
    else if (cautious === 3 && adventurous === 3) {
        return RISK_PROFILE_ADVENTUROUS;
    }
    else if (balanced >= 4) {
        return RISK_PROFILE_BALANCED;
    }
    else if (balanced === 3 && adventurous === 3) {
        return RISK_PROFILE_ADVENTUROUS;
    }
    else if (adventurous >= 4) {
        return RISK_PROFILE_ADVENTUROUS;
    }
}

function getShortTermVolatilitySum(short_term_volatility) {
    let sum = 0;
    Object.entries(short_term_volatility).forEach(data => {
        const [key, item] = data;
        sum += item.value
    });

    return sum;
}
