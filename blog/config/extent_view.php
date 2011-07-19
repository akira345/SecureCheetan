<?php
//ちいたんのビュークラスを拡張する。
    class CMyView extends CView
    {
	//コントローラのGetSqlLogでエスケープしているのでそのまま渡す
		function SetSqlLog( $sqllog )
		{
			if( $this->debug )
			{
				$log	= '<table class="cheetan_sql_log">'
						. '<tr>'
						. '<th width="60%">SQL</th>'
						. '<th width="10%">ERROR</th>'
						. '<th width="10%">ROWS</th>'
						. '<th width="10%">TIME</th>'
						. '</tr>'
						;
				foreach( $sqllog as $name => $rows )
				{
					$log	.= '<tr>'
							. '<td colspan="4"><b>' . $name . '</b></td>'
							. '</tr>'
							;
					foreach( $rows as $i => $row )
					{
						$log	.= '<tr>'
								. '<td>' . $row['query'] . '</td>'
								. '<td>' . $row['error'] . '</td>'
								. '<td>' . $row['affected_rows'] . '</td>'
								. '<td>' . sprintf( '%.5f', $row['query_time'] ) . '</td>'
								. '</tr>'
								;
					}
				}
				$log	.= '</table>';
				$this->variables['cheetan_sql_log'] = $log;
			}
		}
    }

