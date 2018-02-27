<template>
	<row>
		<i-col span="8">
			<i-menu theme="light" active-name="1-1" open-names="[1]" on-select="menuActive" :style="{height:layoutContentHeight+'px',width: '200px'}">
				<submenu name="1">
					<template slot="title">
						<icon type="ios-paper"></icon>
						知识管理
					</template>
					<menu-item name="1-1">
						知识查询
					</menu-item>
					<menu-item name="1-2">
						知识维护
					</menu-item>
				</submenu>
				<submenu name="2">
					<template slot="title">
						<icon type="ios-people"></icon>
						考试管理
					</template>
					<menu-item name="2-1">
						题库管理
					</menu-item>
					<menu-item name="2-2">
						试卷管理
					</menu-item>
					<menu-item name="2-3">
						考试管理
					</menu-item>
					<menu-item name="2-4">
						我的考试
					</menu-item>
				</submenu>
				<submenu name="3">
					<template slot="title">
						<icon type="stats-bars"></icon>
						系统管理
					</template>
					<menu-item name="3-1">
						单位管理
					</menu-item>
					<menu-item name="3-2">
						用户管理
					</menu-item>
				</submenu>
			</i-menu>
		</i-col>
	</row>
</template>