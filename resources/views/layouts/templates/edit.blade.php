<template>
	<modal class="model-add" v-model="modalAdd">
		<i-form :label-width="80">
			<form-item label="标题">
				<i-input v-model="item.title" placeholder="Enter something..."></i-input>
			</form-item>
			<form-item label="型号">
				<i-select v-model="proModel">
					<i-option dis-hover v-for="(item, index) in proModels" :key="index" :value="item" v-text="item"></i-option>
				</i-select>
			</form-item>
			<form-item label="">
				<radio-group v-model="item.ispublish">
					<radio label="male">
						发布
					</radio>
					<radio label="female">
						不发布
					</radio>
				</radio-group>
			</form-item>
			<form-item label="">
				<tree :data="item.tree" :render="renderContent"></tree>
			</form-item>
			<form-item label="内容">
				<i-input v-model="item.content" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Enter something..."></i-input>
			</form-item>
		</i-form>
	</modal>
</template>