
SELECT 
	`transition`.id, `transition`.`name`, 
    transition_role.id_role, transition_role.role_name,
    `REFERENCES`.referenced_transition_id,
    `case`.firm,
    `case`.id, `case`.name,
    `case`.id_pn
FROM  `case` 
LEFT JOIN `transition` ON `case`.id_pn = `transition`.id_pn
LEFT JOIN `REFERENCES` ON `transition`.id = `REFERENCES`.transition_id
LEFT JOIN (
	SELECT * FROM TRANSITIONS_X_ROLE LEFT JOIN `ROLES` ON TRANSITIONS_X_ROLE.id_role = `ROLES`.role_id 
) transition_role ON `transition`.id = transition_role.id_prechod
WHERE 
    `transition`.id_pn = `case`.id_pn
	AND `case`.timestamp_stop IS NULL
	AND EXISTS (    
		SELECT FIRM.firm_id FROM FIRM   
		LEFT JOIN USERS_X_FIRM ON FIRM.firm_id = USERS_X_FIRM.firm_id         
		WHERE FIRM.firm_id = `case`.firm
		AND USERS_X_FIRM.user_id = 1 
	)
    AND(
        EXISTS(
			SELECT * FROM USERS_X_ROLE WHERE user_id = 1 AND firm_id IN (/*TUTO vypisat cez PHP IDcka firiem*/1,2,3)
			AND USERS_X_ROLE.role_id = transition_role.id_role
		)
		OR transition_role.id_role IS NULL 
		OR referenced_transition_id IS NOT NULL 
    );
    
    SELECT firm_id FROM USERS_X_FIRM WHERE user_id = 1;

	