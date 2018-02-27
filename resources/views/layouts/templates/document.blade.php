<template>
	<layout>
		<sider hide-trigger>
			<i-menu active-name="1-2" accordion="true" theme="light" width="auto" :open-names="['1']" :style="{height:layoutContentHeight-38-11-2-11+'px'}">
				<submenu name="1">
					<template slot="title">
						<icon type="ios-navigate"></icon>
						Item 1
					</template>
					<menu-item name="1-1">
						Option 1
					</menu-item>
					<menu-item name="1-2">
						Option 2
					</menu-item>
					<menu-item name="1-3">
						Option 3
					</menu-item>
				</submenu>
				<submenu name="2">
					<template slot="title">
						<icon type="ios-keypad"></icon>
						Item 2
					</template>
					<menu-item name="2-1">
						Option 1
					</menu-item>
					<menu-item name="2-2">
						Option 2
					</menu-item>
				</submenu>
				<submenu name="3">
					<template slot="title">
						<icon type="ios-analytics"></icon>
						Item 3
					</template>
					<menu-item name="3-1">
						Option 1
					</menu-item>
					<menu-item name="3-2">
						Option 2
					</menu-item>
				</submenu>
			</i-menu>
		</sider>
		<layout>
			<breadcrumb :style="{margin: '24px 0'}">
				<breadcrumb-item>
					Home
				</breadcrumb-item>
				<breadcrumb-item>
					Components
				</breadcrumb-item>
				<breadcrumb-item>
					Layout
				</breadcrumb-item>
			</breadcrumb>
			<i-content :style="{padding: '0 11px', minHeight: '280px'}">
				Content
			</i-content>
		</layout>
	</layout>
</template>