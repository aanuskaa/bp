SELECT case_marking.`id`, case_marking.`id_place`, case_marking.`marking` FROM (
	SELECT * FROM arc_PT
	UNION ALL SELECT `from`, `to`, 'inhibitor' as `type` FROM arc_inhibitor
    UNION ALL SELECT `from`, `to`, 'reset' as `type` FROM arc_reset
    ) arcs
LEFT JOIN case_marking ON arcs.`from` = case_marking.id_place
WHERE arcs.`to` = 4 AND case_marking.id_case = 1;          