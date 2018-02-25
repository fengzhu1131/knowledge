<template>
	<card v-for="(doc, idx) in docList" :key="idx" :did="doc.id" class="doc-item">
		<p slot="title" v-text="doc.title">
			<icon type="ios-film-outline"></icon>
		</p>
		<a class="doc-item-btn" href="#" title="编辑" slot="extra" :didx="idx" @click.prevent="editItem">
			<icon type="ivu-icon ivu-icon-edit"></icon>
		</a>
		<a class="doc-item-btn" href="#" title="删除" slot="extra" :didx="idx" @click.prevent="delItem">
			<icon type="ivu-icon ivu-icon-trash-a"></icon>
		</a>
		<p v-text="doc.desc"></p>
		<label class="doc-item-footer">
			<span v-text="doc.date"></span>
		</label>
	</card>
	<Page :total="100" show-sizer></Page>
</template>