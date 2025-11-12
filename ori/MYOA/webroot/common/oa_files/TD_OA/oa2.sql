SELECT
	@rd := @rd + 1 AS ROW_NUM,
	b.*
FROM
	(
		SELECT
			@rd := 0 row_init,
			d.DEPT_NAME,
			u.USER_NAME,
			u.USER_PRIV_NAME,
			yd.ZHD P_SCORE,
			jd.sj11 S_SCORE,
			jd.sj26 B_SCORE
		FROM
			USER u
		LEFT JOIN department d ON d.DEPT_ID = u.dept_id
		LEFT JOIN inhe_kpikh yd ON yd. NAME = u.USER_NAME
		AND yd.BM = d.DEPT_NAME
		LEFT JOIN kpikh jd ON jd. NAME = u.USER_NAME
		AND jd.BM = d.DEPT_NAME
		WHERE
			1 = 1
		AND u.USER_NAME  LIKE '%测试2%'
	) b