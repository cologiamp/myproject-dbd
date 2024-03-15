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

function inRange(x, min, max) {
    return x >= min && x <= max;
}

export function calculateCapacityForLoss(total, type, id) {
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

    axios.put(`/api/risk-outcome/${ id }/assess-outcome`, {
        score: capacityScore,
        type: riskType,
        section: 'capacity_for_loss'
    }).then((response) => {
        console.log(response);
    }).catch((error) => {
        console.log(error);
    });
}

export function calculateKnE(equities) {
    let sum = 0;
    Object.entries(equities).forEach(equity => {
        const [key, item] = equity;
        sum += item.value
    });

    let score = Math.round(sum / equities.length);
    let kneScore = KNE_NONE;

    if (score === 2) {
        kneScore = KNE_SOME;
    } else if (score === 3) {
        kneScore = KNE_GOOD;
    }

    alert(kneScore)
}

export function calculateRiskProfile(stepForm) {
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
    alert('cautious= ' + cautious + 'balanced= ' + balanced + 'adventurous= ' + adventurous )
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
