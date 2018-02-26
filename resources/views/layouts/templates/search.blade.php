<template>
	<i-input autofocus="true" placeholder="输入关键字" v-model="keyword">
		<i-select v-model="proModel" slot="append">
			<i-option dis-hover v-for="(item, index) in proModels" :key="index" :value="item" v-text="item"></i-option>
		</i-select>
		<i-button @click.prevent="btnSearch" slot="append" icon="ios-search"></i-button>
	</i-input>
</template>